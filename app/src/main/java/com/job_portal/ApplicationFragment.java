package com.job_portal;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.content.ContextCompat;
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

public class ApplicationFragment extends Fragment {
    private static final String TAG = "ApplicationFragment"; // Tag for logging

    private LinearLayout applicationListLayout;
    private LinearLayout categoryListLayout;
    private EditText searchBar;
    private Button searchButton;
    private RequestQueue requestQueue;

    // SharedPreferences constants
    private static final String PREFS_NAME = "JobPortalPrefs";
    private static final String PREF_USER_ID = "userId"; // For storing user ID if needed
    private String userId; // Will store the userId as String after converting

    // Define all possible statuses manually for now
    private static final String[] allStatuses = {"Scheduled for Interview", "Rejected", "Pending", "Accepted"};

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.application_fragment_layout, container, false);

        applicationListLayout = view.findViewById(R.id.applicationListLayout);
        categoryListLayout = view.findViewById(R.id.categoryScrollView).findViewById(R.id.categoryListLayout);
        searchBar = view.findViewById(R.id.searchBar);
        searchButton = view.findViewById(R.id.searchButton);
        requestQueue = Volley.newRequestQueue(getContext());

        // Retrieve the userId from SharedPreferences
        SharedPreferences sharedPreferences = getActivity().getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        int userIdInt = sharedPreferences.getInt(PREF_USER_ID, -1); // Default to -1 if not found

        if (userIdInt == -1) {
            Toast.makeText(getContext(), "User not logged in.", Toast.LENGTH_SHORT).show();
            return view; // Exit the method if user is not logged in
        }

        // Convert the Integer userId to String
        userId = String.valueOf(userIdInt);

        // Set click listener for search button
        searchButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                fetchApplications(searchBar.getText().toString(), true, null);
            }
        });

        // Add text change listener to perform real-time search
        searchBar.addTextChangedListener(new android.text.TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                fetchApplications(s.toString(), true, null);
            }

            @Override
            public void afterTextChanged(android.text.Editable s) {
            }
        });

        // Create status filter buttons
        createStatusButtons();

        // Fetch all applications initially
        fetchApplications("", false, null);

        return view;
    }

    // Modified fetchApplications method to include status parameter
    private void fetchApplications(String query, boolean isSearch, @Nullable String status) {
        String url;

        // Check if it's a search request or a status filter request
        if (isSearch) {
            url = "http://10.0.2.2/job_portal_java/search_applications.php?search=" + query + "&user_id=" + userId;
            if (status != null) {
                url += "&status=" + status;  // Add status filter to the URL if available
            }
        } else {
            url = "http://10.0.2.2/job_portal_java/fetch_applications.php?user_id=" + userId;
            if (status != null) {
                url += "&status=" + status;  // Add status filter to the URL if available
            }
        }

        Log.d(TAG, "Request URL: " + url); // Log the request URL
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            JSONArray applications = response.getJSONArray("applications");

                            applicationListLayout.removeAllViews();  // Clear existing applications

                            if (applications.length() == 0) {
                                Toast.makeText(getContext(), "No applications found", Toast.LENGTH_SHORT).show();
                            }

                            for (int i = 0; i < applications.length(); i++) {
                                JSONObject obj = applications.getJSONObject(i);

                                View itemView = LayoutInflater.from(getContext()).inflate(R.layout.application_item_layout, applicationListLayout, false);

                                TextView jobTitle = itemView.findViewById(R.id.jobTitle);
                                TextView companyName = itemView.findViewById(R.id.companyName);
                                TextView applicationStatus = itemView.findViewById(R.id.applicationStatus);
                                ImageView jobImage = itemView.findViewById(R.id.jobImage); // Assuming you have an ImageView in your layout

                                jobTitle.setText(obj.getString("job_title"));
                                companyName.setText(obj.getString("company_name"));

                                String status = obj.getString("status");
                                applicationStatus.setText(status);

                                // Set color based on status
                                if (status.equalsIgnoreCase("Scheduled for Interview")) {
                                    applicationStatus.setTextColor(ContextCompat.getColor(getContext(), R.color.green)); // Green color
                                } else {
                                    applicationStatus.setTextColor(ContextCompat.getColor(getContext(), R.color.red)); // Red color for other statuses
                                }

                                String jobImg = obj.getString("job_img");
                                if (!jobImg.isEmpty()) {
                                    Picasso.get()
                                            .load("http://10.0.2.2/job_portal_java/Admin/" + jobImg)
                                            .placeholder(R.drawable.loading_image)
                                            .error(R.drawable.error_icon)
                                            .into(jobImage);
                                } else {
                                    jobImage.setImageResource(R.mipmap.app_logo); // Default image
                                }

                                itemView.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View view) {
                                        Intent intent = new Intent(getActivity(), JobDetailActivity.class);
                                        try {
                                            intent.putExtra("job_id", obj.getString("job_id"));
                                            intent.putExtra("job_title", obj.getString("job_title"));
                                            intent.putExtra("company_name", obj.getString("company_name"));
                                            intent.putExtra("job_img", obj.getString("job_img"));
                                        } catch (JSONException e) {
                                            Log.e(TAG, "Error parsing job details: " + e.getMessage());
                                        }
                                        startActivity(intent);
                                    }
                                });

                                applicationListLayout.addView(itemView);
                            }
                        } catch (JSONException e) {
                            Log.e(TAG, "Error parsing JSON response: " + e.getMessage());
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Error: " + error.getMessage());
                Toast.makeText(getContext(), "Error fetching data.", Toast.LENGTH_SHORT).show();
            }
        });

        requestQueue.add(jsonObjectRequest);
    }

    // Method to dynamically create status filter buttons
    private void createStatusButtons() {
        for (String status : allStatuses) {
            Button statusButton = new Button(getContext());
            statusButton.setText(status);
            statusButton.setLayoutParams(new LinearLayout.LayoutParams(
                    LinearLayout.LayoutParams.WRAP_CONTENT,
                    LinearLayout.LayoutParams.WRAP_CONTENT));
            statusButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    fetchApplications("", true, status);  // Pass status to filter applications
                }
            });
            categoryListLayout.addView(statusButton);
        }
    }
}
