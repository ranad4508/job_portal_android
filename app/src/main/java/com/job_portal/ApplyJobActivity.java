package com.job_portal;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

public class ApplyJobActivity extends AppCompatActivity {
    private TextView jobTitle, companyName, resumeFileName, successMessage;
    private ImageView jobImage, backToJobDetail, shareButton;
    private Button applyButton;
    private LinearLayout uploadResumeLayout;
    private ProgressDialog progressDialog;

    private static final String UPLOAD_URL = "http://10.0.2.2/job_portal_java/job_applications.php";
    private static final String PREFS_NAME = "JobPortalPrefs";
    private static final String PREF_USER_ID = "userId";
    private static final int PICK_FILE_REQUEST = 1;

    private Uri resumeUri;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.apply_job_activity);

        // Initialize Views
        jobTitle = findViewById(R.id.jobTitle);
        companyName = findViewById(R.id.companyName);
        jobImage = findViewById(R.id.applyJobImage);
        backToJobDetail = findViewById(R.id.backToJobDetail);
        shareButton = findViewById(R.id.shareJobDetail);
        applyButton = findViewById(R.id.applyButton);
        uploadResumeLayout = findViewById(R.id.uploadResumeLayout);
        resumeFileName = findViewById(R.id.resumeFileName);
        successMessage = findViewById(R.id.successMessage);

        // Initialize ProgressDialog
        progressDialog = new ProgressDialog(this);
        progressDialog.setMessage("Applying for job...");

        // Retrieve data from Intent
        Intent intent = getIntent();
        String jobId = intent.getStringExtra("JOB_ID");
        String company = intent.getStringExtra("COMPANY_NAME");
        String title = intent.getStringExtra("JOB_TITLE");
        String imageUrl = intent.getStringExtra("JOB_IMG");

        // Update UI with received data
        jobTitle.setText(title);
        companyName.setText(company);

        if (imageUrl != null && !imageUrl.isEmpty()) {
            Log.d("ApplyJobActivity", "Image URL: " + imageUrl);
            Picasso.get()
                    .load(imageUrl)
                    .placeholder(R.drawable.loading_image)
                    .error(R.drawable.error_icon)
                    .into(jobImage);
        } else {
            jobImage.setImageResource(R.mipmap.app_logo);
        }

        // Set up OnClickListener for Back button
        backToJobDetail.setOnClickListener(v -> {
            Intent backIntent = new Intent(ApplyJobActivity.this, JobDetailActivity.class);
            backIntent.putExtra("JOB_ID", jobId);
            startActivity(backIntent);
        });

        // Set up OnClickListener for Share button
        shareButton.setOnClickListener(v -> shareJobDetails(title, company));

        // Set up OnClickListener for Upload Resume Layout
        uploadResumeLayout.setOnClickListener(v -> openFilePicker());

        // Set up OnClickListener for Apply button
        applyButton.setOnClickListener(v -> {
            Log.d("ApplyJobActivity", "Apply button clicked");
            applyForJob(jobId, company, title);
        });
    }

    private void shareJobDetails(String title, String company) {
        String shareBody = "Check out this job opening at " + company + ": " + title + ".\n";

        Intent sharingIntent = new Intent(Intent.ACTION_SEND);
        sharingIntent.setType("text/plain");
        sharingIntent.putExtra(Intent.EXTRA_SUBJECT, "Job Opening at " + company);
        sharingIntent.putExtra(Intent.EXTRA_TEXT, shareBody);

        startActivity(Intent.createChooser(sharingIntent, "Share via"));
    }

    private void openFilePicker() {
        Intent intent = new Intent(Intent.ACTION_GET_CONTENT);
        intent.setType("*/*");
        startActivityForResult(Intent.createChooser(intent, "Select Resume/CV"), PICK_FILE_REQUEST);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == PICK_FILE_REQUEST && resultCode == RESULT_OK && data != null) {
            resumeUri = data.getData();
            String fileName = getFileName(resumeUri);
            resumeFileName.setText("Selected file: " + fileName);
            Log.d("ApplyJobActivity", "Selected file URI: " + resumeUri.toString());
            Toast.makeText(this, "File selected: " + fileName, Toast.LENGTH_SHORT).show();
        } else {
            Log.d("ApplyJobActivity", "File selection failed or cancelled.");
        }
    }

    private String getFileName(Uri uri) {
        // You can use this to get the display name of the selected file
        String filePath = uri.getLastPathSegment();
        return filePath != null ? filePath : "Unknown file";
    }

    private void applyForJob(String jobId, String companyName, String jobTitle) {
        if (resumeUri == null) {
            Toast.makeText(this, "Please select a resume to upload", Toast.LENGTH_SHORT).show();
            return;
        }

        SharedPreferences preferences = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        int userId = preferences.getInt(PREF_USER_ID, -1);

        if (userId == -1) {
            Toast.makeText(this, "User not logged in", Toast.LENGTH_SHORT).show();
            return;
        }

        // Get file path (Google Drive or local) to send to the server
        String filePath = resumeUri.toString();  // Get the URI as a string to store the path
        Log.d("ApplyJobActivity", "File path to store: " + filePath);

        progressDialog.show();

        // Create the JSON request body
        JSONObject requestBody = new JSONObject();
        try {
            requestBody.put("job_id", jobId);
            requestBody.put("company_name", companyName);
            requestBody.put("job_title", jobTitle);
            requestBody.put("user_id", userId);
            requestBody.put("resume_file", filePath);  // Add the file path as a string
        } catch (JSONException e) {
            e.printStackTrace();
            progressDialog.dismiss();
            Toast.makeText(this, "Error creating request", Toast.LENGTH_SHORT).show();
            return;
        }

        // Create the StringRequest using Volley
        StringRequest stringRequest = new StringRequest(Request.Method.POST, UPLOAD_URL,
                response -> {
                    progressDialog.dismiss();
                    try {
                        JSONObject jsonResponse = new JSONObject(response);
                        if (jsonResponse.getBoolean("success")) {
                            successMessage.setVisibility(View.VISIBLE);
                            successMessage.setText("Application submitted successfully!");
                        } else {
                            Toast.makeText(ApplyJobActivity.this, "Failed to apply", Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        Toast.makeText(ApplyJobActivity.this, "Response error", Toast.LENGTH_SHORT).show();
                    }
                },
                error -> {
                    progressDialog.dismiss();
                    Toast.makeText(ApplyJobActivity.this, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
                }) {
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public byte[] getBody() {
                return requestBody.toString().getBytes();
            }
        };

        // Add the request to the Volley queue
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);
    }
}
