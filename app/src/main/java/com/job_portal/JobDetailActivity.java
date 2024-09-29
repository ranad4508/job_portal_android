package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

public class JobDetailActivity extends AppCompatActivity {
    private static final String TAG = "JobDetailActivity";
    private ImageView backToHome, companyLogo, shareButton;
    private Button applyNow;
    private TextView jobTitle, jobSalary, jobType, jobLocation, jobRequirements, jobPostedDate, jobDeadlineDate, companyName;
    private RequestQueue requestQueue;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.job_detail_activity);

        // Initialize Views
        backToHome = findViewById(R.id.backToHome);
        applyNow = findViewById(R.id.applyNow);
        jobTitle = findViewById(R.id.jobTitle);
        jobSalary = findViewById(R.id.salaryValue);
        jobType = findViewById(R.id.jobTypeValue);
        jobLocation = findViewById(R.id.locationValue);
        jobRequirements = findViewById(R.id.requirements);
        jobPostedDate = findViewById(R.id.postedDate);
        jobDeadlineDate = findViewById(R.id.deadlineDate);
        companyName = findViewById(R.id.companyName);
        companyLogo = findViewById(R.id.companyLogo);
        shareButton = findViewById(R.id.shareJobDetail); // Ensure this ID exists in layout

        // Initialize Volley RequestQueue
        requestQueue = Volley.newRequestQueue(this);

        // Retrieve job details from Intent
        Intent intent = getIntent();
        String jobId = intent.getStringExtra("JOB_ID");
        if (jobId != null) {
            fetchJobDetails(jobId); // Fetch job details from API
        }

        // Set up OnClickListener for Back button
        backToHome.setOnClickListener(v -> {
            Intent homeIntent = new Intent(JobDetailActivity.this, HomeActivity.class);
            startActivity(homeIntent);
        });

        // Set up OnClickListener for Apply Now button
        applyNow.setOnClickListener(v -> {
            Intent applyIntent = new Intent(JobDetailActivity.this, ApplyJobActivity.class);
            applyIntent.putExtra("JOB_ID", jobId);
            applyIntent.putExtra("COMPANY_NAME", companyName.getText().toString());
            applyIntent.putExtra("JOB_TITLE", jobTitle.getText().toString());
            applyIntent.putExtra("JOB_IMG", companyLogo.getTag().toString());
            startActivity(applyIntent);
        });

        // Set up OnClickListener for Share button
        shareButton.setOnClickListener(v -> shareJobDetails());
    }

    private void fetchJobDetails(String jobId) {
        String url = "http://10.0.2.2/job_portal_java/select_job_details.php?job_id=" + jobId;

        StringRequest stringRequest = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d(TAG, "Response: " + response);

                        try {
                            JSONObject jsonResponse = new JSONObject(response);
                            if (jsonResponse.has("error")) {
                                String errorMessage = jsonResponse.getString("error");
                                Toast.makeText(JobDetailActivity.this, errorMessage, Toast.LENGTH_SHORT).show();
                                return;
                            }

                            // Parse JSON response
                            String title = jsonResponse.optString("job_title", "N/A");
                            String salary = jsonResponse.optString("job_salary", "N/A");
                            String type = jsonResponse.optString("job_type", "N/A");
                            String company = jsonResponse.optString("company_name", "N/A");
                            String companyImage = jsonResponse.optString("job_img", "");
                            String location = jsonResponse.optString("job_location", "N/A");
                            String requirements = jsonResponse.optString("job_requirements", "N/A");
                            String postedDate = jsonResponse.optString("posted_date", "N/A");
                            String deadlineDate = jsonResponse.optString("deadline_date", "N/A");

                            // Update UI
                            jobTitle.setText(title);
                            jobSalary.setText(salary);
                            jobType.setText(type);
                            jobLocation.setText(location);
                            companyName.setText(company);
                            jobRequirements.setText(requirements);
                            jobPostedDate.setText(postedDate);
                            jobDeadlineDate.setText(deadlineDate);

                            if (!companyImage.isEmpty()) {
                                String imageUrl = "http://10.0.2.2/job_portal_java/Admin/" + companyImage;
                                Picasso.get()
                                        .load(imageUrl)
                                        .placeholder(R.drawable.loading_image)
                                        .error(R.drawable.error_icon)
                                        .into(companyLogo, new com.squareup.picasso.Callback() {
                                            @Override
                                            public void onSuccess() {
                                                Log.d(TAG, "Image loaded successfully.");
                                                companyLogo.setTag(imageUrl); // Set image URL as tag
                                            }

                                            @Override
                                            public void onError(Exception e) {
                                                Log.e(TAG, "Error loading image: " + e.getMessage());
                                                companyLogo.setImageResource(R.mipmap.app_logo); // Default image on error
                                            }
                                        });
                            } else {
                                companyLogo.setImageResource(R.mipmap.app_logo); // Default image
                                companyLogo.setTag(""); // Clear tag
                            }
                            Log.d(TAG, "Image URL: " + "http://10.0.2.2/job_portal_java/Admin/" + companyImage);
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(JobDetailActivity.this, "Error parsing data", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e(TAG, "Error: " + error.toString());
                        Toast.makeText(JobDetailActivity.this, "Error fetching data", Toast.LENGTH_SHORT).show();
                    }
                });

        // Add the request to the RequestQueue.
        requestQueue.add(stringRequest);
    }

    private void shareJobDetails() {
        String shareBody = "Check out this job opening:\n" +
                "Title: " + jobTitle.getText().toString() + "\n" +
                "Company Name: " + companyName.getText().toString() + "\n" +
                "Salary: " + jobSalary.getText().toString() + "\n" +
                "Type: " + jobType.getText().toString() + "\n" +
                "Location: " + jobLocation.getText().toString() + "\n" +
                "Requirements: " + jobRequirements.getText().toString();

        Intent sharingIntent = new Intent(Intent.ACTION_SEND);
        sharingIntent.setType("text/plain");
        sharingIntent.putExtra(Intent.EXTRA_SUBJECT, "Job Opening Details");
        sharingIntent.putExtra(Intent.EXTRA_TEXT, shareBody);

        startActivity(Intent.createChooser(sharingIntent, "Share via"));
    }
}
