package com.job_portal;

import android.app.DatePickerDialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentTransaction;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import org.json.JSONException;
import org.json.JSONObject;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;

public class EditProfileActivity extends AppCompatActivity {

    private static final String TAG = "EditProfileActivity";
    private static final String FETCH_URL = "http://10.0.2.2/job_portal_java/edit_users.php"; // Backend API URL
    private static final String UPDATE_URL = "http://10.0.2.2/job_portal_java/edit_users.php";

    private EditText etFirstName, etLastName, etEmail, etPhone, etDob, etAddress, etOccupation;
    private ImageView profileImage, backToHome;
    private Button btnUpdate;
    private int userId;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.edit_profile_layout);

        // Initialize views
        etFirstName = findViewById(R.id.firstname);
        etLastName = findViewById(R.id.lastname);
        etEmail = findViewById(R.id.et_email);
        etPhone = findViewById(R.id.et_phone);
        etDob = findViewById(R.id.et_dob);
        etAddress = findViewById(R.id.et_address);
        etOccupation = findViewById(R.id.et_occupation);
        profileImage = findViewById(R.id.profile_image);
        btnUpdate = findViewById(R.id.btn_update);
        backToHome = findViewById(R.id.backToHome);

        // Get user ID from shared preferences
        userId = getSharedPreferences("JobPortalPrefs", MODE_PRIVATE).getInt("userId", -1);

        // Fetch user details and display them
        fetchUserDetails();
        backToHome.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                navigateToProfileFragment();
            }
        });

        // Set up button click listener
        btnUpdate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                updateProfile();
            }
        });

        // Set up DatePicker for the date of birth field
        etDob.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showDatePickerDialog();
            }
        });
    }
    private void navigateToProfileFragment() {
        ProfileFragment profileFragment = new ProfileFragment();
        FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
        transaction.replace(R.id.profile_container, profileFragment); // Use your container ID
        transaction.addToBackStack(null); // Optional: Add this transaction to the back stack
        transaction.commit();
    }

    // Show DatePickerDialog when DOB field is clicked
    private void showDatePickerDialog() {
        // Get the current date
        final Calendar calendar = Calendar.getInstance();
        int year = calendar.get(Calendar.YEAR);
        int month = calendar.get(Calendar.MONTH);
        int day = calendar.get(Calendar.DAY_OF_MONTH);

        // Create a DatePickerDialog and set the selected date to the EditText field
        DatePickerDialog datePickerDialog = new DatePickerDialog(this, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
                // Note: monthOfYear starts from 0, so we need to add 1 for the correct month
                String selectedDate = year + "-" + (monthOfYear + 1) + "-" + dayOfMonth;
                etDob.setText(selectedDate); // Set the selected date to the EditText
            }
        }, year, month, day);

        // Show the DatePickerDialog
        datePickerDialog.show();
    }

    private void fetchUserDetails() {
        // Show a loading dialog
        ProgressDialog progressDialog = new ProgressDialog(this);
        progressDialog.setMessage("Loading profile...");
        progressDialog.show();

        // Create a StringRequest for fetching user details
        StringRequest stringRequest = new StringRequest(Request.Method.POST, FETCH_URL, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                progressDialog.dismiss();
                try {
                    // Parse the JSON response
                    JSONObject jsonResponse = new JSONObject(response);
                    if (jsonResponse.getString("status").equals("success")) {
                        JSONObject userData = jsonResponse.getJSONObject("data");

                        // Set the user data to the EditText fields
                        etFirstName.setText(userData.getString("firstname"));
                        etLastName.setText(userData.getString("lastname"));
                        etEmail.setText(userData.getString("email"));
                        etPhone.setText(userData.getString("phone"));
                        etDob.setText(userData.getString("date_of_birth"));
                        etAddress.setText(userData.getString("address"));
                        etOccupation.setText(userData.getString("occupation"));
                    } else {
                        Toast.makeText(EditProfileActivity.this, "Failed to load profile", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(EditProfileActivity.this, "Error parsing profile data", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Log.e(TAG, "Error: " + error.getMessage());
                Toast.makeText(EditProfileActivity.this, "Error loading profile", Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                // Send the user ID to fetch data
                Map<String, String> params = new HashMap<>();
                params.put("user_id", String.valueOf(userId));
                params.put("action", "fetch"); // Specify the fetch action
                return params;
            }
        };

        // Add the request to the Volley request queue
        Volley.newRequestQueue(this).add(stringRequest);
    }

    private void updateProfile() {
        // Show a loading dialog
        ProgressDialog progressDialog = new ProgressDialog(this);
        progressDialog.setMessage("Updating profile...");
        progressDialog.show();

        // Get user input
        String firstName = etFirstName.getText().toString().trim();
        String lastName = etLastName.getText().toString().trim();
        String email = etEmail.getText().toString().trim();
        String phone = etPhone.getText().toString().trim();
        String dob = etDob.getText().toString().trim();
        String address = etAddress.getText().toString().trim();
        String occupation = etOccupation.getText().toString().trim();

        // Create a StringRequest for updating the profile
        StringRequest stringRequest = new StringRequest(Request.Method.POST, UPDATE_URL, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                progressDialog.dismiss();
                try {
                    // Parse the JSON response
                    JSONObject jsonResponse = new JSONObject(response);
                    if (jsonResponse.getString("status").equals("success")) {
                        Toast.makeText(EditProfileActivity.this, "Profile updated successfully", Toast.LENGTH_SHORT).show();
                        // Finish activity and return to ProfileFragment
                        finish();
                    } else {
                        Toast.makeText(EditProfileActivity.this, "Failed to update profile", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(EditProfileActivity.this, "Error parsing response", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Log.e(TAG, "Error: " + error.getMessage());
                Toast.makeText(EditProfileActivity.this, "Error updating profile", Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                // Send data to the server
                Map<String, String> params = new HashMap<>();
                params.put("user_id", String.valueOf(userId));
                if (!firstName.isEmpty()) params.put("firstname", firstName);
                if (!lastName.isEmpty()) params.put("lastname", lastName);
                if (!email.isEmpty()) params.put("email", email);
                if (!phone.isEmpty()) params.put("phone", phone);
                if (!dob.isEmpty()) params.put("date_of_birth", dob);
                if (!address.isEmpty()) params.put("address", address);
                if (!occupation.isEmpty()) params.put("occupation", occupation);

                return params;
            }
        };

        // Add the request to the Volley request queue
        Volley.newRequestQueue(this).add(stringRequest);
    }
}
