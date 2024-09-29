package com.job_portal;


import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.text.Editable;
import android.text.TextWatcher;
import android.widget.ProgressBar;

public class JobRecommendationActivity extends AppCompatActivity {

    private ImageView backToHome;
    private LinearLayout jobCategoriesContainer, jobItemContainer;
    private EditText searchInput;
    private Button searchButton, seeAllButton;
    private RequestQueue requestQueue;
    private ProgressBar progressBar;  // Add a ProgressBar for loading indication

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.job_recommendation);

        backToHome = findViewById(R.id.backToHome);
        jobCategoriesContainer = findViewById(R.id.jobCategoriesContainer);
        jobItemContainer = findViewById(R.id.jobItemContainer);
        searchInput = findViewById(R.id.searchInput);
        searchButton = findViewById(R.id.searchButton);
        seeAllButton = findViewById(R.id.seeAllButton);
        progressBar = findViewById(R.id.progressBar);  // Initialize ProgressBar

        requestQueue = Volley.newRequestQueue(this);

        // Back to Home Button
        backToHome.setOnClickListener(view -> {
            Intent intent = new Intent(JobRecommendationActivity.this, HomeActivity.class);
            startActivity(intent);
        });

        // Fetch categories and jobs on activity start
        fetchJobCategories();
        fetchJobData(null);  // Fetch all jobs by default

        // Real-time search functionality
        searchInput.addTextChangedListener(new TextWatcher() {
            private static final long DEBOUNCE_DELAY = 300; // 300 ms delay
            private Handler handler = new Handler(Looper.getMainLooper());
            private Runnable searchRunnable;

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                // No action needed here
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (searchRunnable != null) {
                    handler.removeCallbacks(searchRunnable);
                }
                searchRunnable = () -> {
                    String query = s.toString();
                    if (!query.isEmpty()) {
                        searchJobs(query);
                    } else {
                        // If search query is empty, show all jobs
                        fetchJobData(null);
                    }
                };
                handler.postDelayed(searchRunnable, DEBOUNCE_DELAY);
            }

            @Override
            public void afterTextChanged(Editable s) {
                // No action needed here
            }
        });

        // "See All" functionality
        seeAllButton.setOnClickListener(view -> {
            searchInput.setText("");  // Clear search input
            fetchJobData(null);  // Fetch all jobs again
        });
    }

    // Fetch and display job categories in a horizontal scroll view
    private void fetchJobCategories() {
        String url = "http://10.0.2.2/job_portal_java/select_job_categories.php";

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                response -> {
                    try {
                        if (response.getBoolean("success")) {
                            JSONArray categoriesArray = response.getJSONArray("categories");
                            displayJobCategories(categoriesArray);
                        } else {
                            Toast.makeText(JobRecommendationActivity.this, "No categories found", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(JobRecommendationActivity.this, "Error parsing categories", Toast.LENGTH_SHORT).show();
                    }
                }, error -> Toast.makeText(JobRecommendationActivity.this, "Error fetching categories", Toast.LENGTH_SHORT).show());

        // Add the request to the queue
        requestQueue.add(jsonObjectRequest);
    }

    // Display job categories in horizontal scroll view
    private void displayJobCategories(JSONArray categoriesArray) throws JSONException {
        jobCategoriesContainer.removeAllViews(); // Clear previous categories

        // Create a "See All" button to show all jobs
        Button seeAllCategoryButton = new Button(this);
        seeAllCategoryButton.setText("See All");
        seeAllCategoryButton.setOnClickListener(v -> fetchJobData(null));  // Fetch all jobs
        jobCategoriesContainer.addView(seeAllCategoryButton);  // Add the button to the category container

        for (int i = 0; i < categoriesArray.length(); i++) {
            JSONObject category = categoriesArray.getJSONObject(i);
            int categoryId = category.getInt("category_id");
            String categoryName = category.getString("category_name");

            Button categoryButton = new Button(this);
            categoryButton.setText(categoryName);

            categoryButton.setOnClickListener(v -> filterJobsByCategory(categoryId));

            jobCategoriesContainer.addView(categoryButton);
        }
    }

    // Fetch job data (category filter can be null to fetch all jobs)
    private void fetchJobData(@Nullable Integer categoryId) {
        String url = "http://10.0.2.2/job_portal_java/select_jobs.php";

        if (categoryId != null && categoryId > 0) {
            url += "?category=" + categoryId;
        }

        progressBar.setVisibility(View.VISIBLE); // Show ProgressBar

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                response -> {
                    progressBar.setVisibility(View.GONE); // Hide ProgressBar
                    try {
                        if (response.getBoolean("success")) {
                            JSONArray jobsArray = response.getJSONArray("jobs");
                            displayJobs(jobsArray);
                        } else {
                            Toast.makeText(JobRecommendationActivity.this, "No jobs found", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(JobRecommendationActivity.this, "Error parsing job data", Toast.LENGTH_SHORT).show();
                    }
                }, error -> {
            progressBar.setVisibility(View.GONE); // Hide ProgressBar
            Toast.makeText(JobRecommendationActivity.this, "Error fetching job data", Toast.LENGTH_SHORT).show();
        });

        requestQueue.add(jsonObjectRequest);
    }

    // Display jobs in the view
    private void displayJobs(JSONArray jobsArray) throws JSONException {
        jobItemContainer.removeAllViews(); // Clear previous job items

        for (int i = 0; i < jobsArray.length(); i++) {
            JSONObject job = jobsArray.getJSONObject(i);

            String jobId = job.getString("job_id");
            String title = job.getString("title");
            String description = job.getString("description");
            String location = job.getString("location");
            String salary = job.getString("salary");
            String jobImg = job.getString("job_img");

            View jobItemView = getLayoutInflater().inflate(R.layout.job_item_layout, null);
            TextView jobTitle = jobItemView.findViewById(R.id.jobTitle);
            TextView jobDesc = jobItemView.findViewById(R.id.jobDesc);
            TextView jobLocation = jobItemView.findViewById(R.id.jobLocation);
            TextView jobSalary = jobItemView.findViewById(R.id.jobSalary);
            ImageView jobImageView = jobItemView.findViewById(R.id.jobImage);

            jobTitle.setText(title);
            jobDesc.setText(description);
            jobLocation.setText(location);
            jobSalary.setText(salary);

            // Load job image using Picasso
            if (!jobImg.isEmpty()) {
                Picasso.get()
                        .load("http://10.0.2.2/job_portal_java/Admin/" + jobImg)
                        .placeholder(R.drawable.loading_image)
                        .error(R.drawable.error_icon)
                        .into(jobImageView);
            } else {
                jobImageView.setImageResource(R.mipmap.app_logo); // Default image
            }

            // Set OnClickListener for job item
            jobItemView.setOnClickListener(v -> {
                Intent intent = new Intent(JobRecommendationActivity.this, JobDetailActivity.class);
                intent.putExtra("JOB_ID", jobId);
                startActivity(intent);
            });

            jobItemContainer.addView(jobItemView);
        }
    }

    // Filter jobs by category
    private void filterJobsByCategory(int categoryId) {
        fetchJobData(categoryId);
    }

    // Search jobs by title
    private void searchJobs(String query) {
        String url = "http://10.0.2.2/job_portal_java/search_jobs.php?query=" + query;

        progressBar.setVisibility(View.VISIBLE); // Show ProgressBar

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                response -> {
                    progressBar.setVisibility(View.GONE); // Hide ProgressBar
                    try {
                        if (response.getBoolean("success")) {
                            JSONArray jobsArray = response.getJSONArray("jobs");

                            if (jobsArray.length() > 0) {
                                displayJobs(jobsArray);
                            } else {
                                // No jobs found, show a message
                                Toast.makeText(JobRecommendationActivity.this, "No jobs found for your search", Toast.LENGTH_SHORT).show();
                                jobItemContainer.removeAllViews();  // Clear the previous job items
                                TextView noResultsView = new TextView(this);
                                noResultsView.setText("No jobs matching your search term were found.");
                                jobItemContainer.addView(noResultsView);
                            }
                        } else {
                            Toast.makeText(JobRecommendationActivity.this, "No jobs found for your search", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(JobRecommendationActivity.this, "Error parsing search results", Toast.LENGTH_SHORT).show();
                    }
                }, error -> {
            progressBar.setVisibility(View.GONE); // Hide ProgressBar
            Toast.makeText(JobRecommendationActivity.this, "Error fetching search results", Toast.LENGTH_SHORT).show();
        });

        requestQueue.add(jsonObjectRequest);
    }
}
