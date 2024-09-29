package com.job_portal;

import android.content.Context;
import android.content.Intent;
import android.graphics.Rect;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class HomeFragment extends Fragment {

    private EditText searchInput;
    private Button searchButton;
    private ProgressBar progressBar;
    private LinearLayout jobItemContainer, jobCategoriesContainer;
    private RequestQueue requestQueue;
    private TextView seeAllTips, seeAllJobs;

    private JSONArray originalJobsArray; // To store original job data

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.home_fragment_layout, container, false);

        searchInput = view.findViewById(R.id.searchText);
        searchButton = view.findViewById(R.id.searchButton);
        progressBar = view.findViewById(R.id.progressBar);
        jobItemContainer = view.findViewById(R.id.jobItemContainer);
        jobCategoriesContainer = view.findViewById(R.id.jobCategoriesContainer);

        requestQueue = Volley.newRequestQueue(requireContext());
        seeAllTips = view.findViewById(R.id.seeAllTips);
        seeAllJobs = view.findViewById(R.id.seeAllJobs);

        // Fetch job categories
        fetchJobCategories();

        // Fetch all jobs by default
        fetchJobData(null);

        setupListeners();

        seeAllTips.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), TipsActivity.class);
                startActivity(intent);
            }
        });
        seeAllJobs.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), JobRecommendationActivity.class);
                startActivity(intent);
            }
        });

        // Add TextWatcher for real-time search
        searchInput.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) { }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (s.length() > 2) { // Start searching after 3 characters
                    searchJobs(s.toString());
                } else {
                    if (originalJobsArray != null) {
                        try {
                            displayJobs(originalJobsArray);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }
            }

            @Override
            public void afterTextChanged(Editable s) { }
        });

        // Search button click listener
        searchButton.setOnClickListener(v -> {
            String query = searchInput.getText().toString().trim();
            if (!query.isEmpty()) {
                searchJobs(query);
            } else {
                Toast.makeText(getActivity(), "Please enter a search term", Toast.LENGTH_SHORT).show();
            }
        });

        // Touch listener to hide keyboard when touching outside of search box
        view.setOnTouchListener((v, event) -> {
            if (event.getAction() == MotionEvent.ACTION_DOWN) {
                View focusedView = getActivity().getCurrentFocus();
                if (focusedView != null) {
                    Rect rect = new Rect();
                    focusedView.getGlobalVisibleRect(rect);
                    if (!rect.contains((int) event.getRawX(), (int) event.getRawY())) {
                        InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
                        if (imm != null) {
                            imm.hideSoftInputFromWindow(focusedView.getWindowToken(), 0);
                        }
                        searchInput.clearFocus(); // Optional: Clear focus from search box
                    }
                }
            }
            return false;
        });

        return view;
    }

    private void setupListeners() {
        // Your existing listeners if any
    }

    private void fetchJobCategories() {
        String url = "http://10.0.2.2/job_portal_java/select_job_categories.php";

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                response -> {
                    try {
                        if (response.getBoolean("success")) {
                            JSONArray categoriesArray = response.getJSONArray("categories");
                            displayJobCategories(categoriesArray);
                        } else {
                            Toast.makeText(getActivity(), "No categories found", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(getActivity(), "Error parsing categories", Toast.LENGTH_SHORT).show();
                    }
                }, error -> Toast.makeText(getActivity(), "Error fetching categories", Toast.LENGTH_SHORT).show());

        requestQueue.add(jsonObjectRequest);
    }

    private void displayJobCategories(JSONArray categoriesArray) throws JSONException {
        jobCategoriesContainer.removeAllViews(); // Clear previous categories

        for (int i = 0; i < categoriesArray.length(); i++) {
            JSONObject category = categoriesArray.getJSONObject(i);
            int categoryId = category.getInt("category_id");
            String categoryName = category.getString("category_name");

            Button categoryButton = new Button(getContext());
            categoryButton.setText(categoryName);

            categoryButton.setOnClickListener(v -> filterJobsByCategory(categoryId));

            jobCategoriesContainer.addView(categoryButton);
        }
    }

    private void fetchJobData(@Nullable Integer categoryId) {
        String url = "http://10.0.2.2/job_portal_java/select_jobs.php";

        if (categoryId != null && categoryId > 0) {
            url += "?category=" + categoryId;
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                response -> {
                    try {
                        if (response.getBoolean("success")) {
                            JSONArray jobsArray = response.getJSONArray("jobs");
                            originalJobsArray = jobsArray; // Store the original job data
                            displayJobs(jobsArray);
                        } else {
                            Toast.makeText(getActivity(), "No jobs found", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(getActivity(), "Error parsing job data", Toast.LENGTH_SHORT).show();
                    }
                }, error -> Toast.makeText(getActivity(), "Error fetching job data", Toast.LENGTH_SHORT).show());

        requestQueue.add(jsonObjectRequest);
    }

    private void displayJobs(JSONArray jobsArray) throws JSONException {
        jobItemContainer.removeAllViews(); // Clear previous job items

        if (jobsArray.length() == 0) {
            TextView noResultsView = new TextView(getActivity());
            noResultsView.setText("No jobs found.");
            jobItemContainer.addView(noResultsView);
        } else {
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

                if (!jobImg.isEmpty()) {
                    Picasso.get()
                            .load("http://10.0.2.2/job_portal_java/Admin/" + jobImg)
                            .placeholder(R.drawable.loading_image)
                            .error(R.drawable.error_icon)
                            .into(jobImageView);
                } else {
                    jobImageView.setImageResource(R.mipmap.app_logo); // Default image
                }

                jobItemView.setOnClickListener(v -> {
                    Intent intent = new Intent(getActivity(), JobDetailActivity.class);
                    intent.putExtra("JOB_ID", jobId);
                    startActivity(intent);
                });

                jobItemContainer.addView(jobItemView);
            }
        }
    }

    private void filterJobsByCategory(int categoryId) {
        fetchJobData(categoryId);
    }

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
                                Toast.makeText(getActivity(), "No jobs found for your search", Toast.LENGTH_SHORT).show();
                                jobItemContainer.removeAllViews();  // Clear the previous job items
                                TextView noResultsView = new TextView(getActivity());
                                noResultsView.setText("No jobs matching your search term were found.");
                                jobItemContainer.addView(noResultsView);
                            }
                        } else {
                            Toast.makeText(getActivity(), "No jobs found for your search", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(getActivity(), "Error parsing search results", Toast.LENGTH_SHORT).show();
                    }
                }, error -> {
            progressBar.setVisibility(View.GONE); // Hide ProgressBar
            Toast.makeText(getActivity(), "Error fetching search results", Toast.LENGTH_SHORT).show();
        });

        requestQueue.add(jsonObjectRequest);
    }
}
