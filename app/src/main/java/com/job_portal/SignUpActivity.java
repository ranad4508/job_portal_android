package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
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

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class SignUpActivity extends AppCompatActivity {
    ImageView backToLogin;
    TextView goBackToLogin;
    private Button signUpButton;
    private EditText firstName, lastName, email, phoneNumber, password, confirmPassword;
    private CheckBox termsAndCondition;
    private RequestQueue requestQueue;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.signup_activity);

        // Initialize views
        backToLogin = findViewById(R.id.backToLogin);
        goBackToLogin = findViewById(R.id.goBackToLogin);
        firstName = findViewById(R.id.firstname);
        lastName = findViewById(R.id.lastname);
        email = findViewById(R.id.email);
        phoneNumber = findViewById(R.id.phoneNumber);
        password = findViewById(R.id.password);
        confirmPassword = findViewById(R.id.confirmPassword);
        termsAndCondition = findViewById(R.id.termsAndCondition);
        signUpButton = findViewById(R.id.signUpButton);

        // Initialize RequestQueue
        requestQueue = Volley.newRequestQueue(this);

        // Set click listeners for navigation
        backToLogin.setOnClickListener(view -> navigateToLogin());
        goBackToLogin.setOnClickListener(view -> navigateToLogin());

        // Set click listener for sign-up button
        signUpButton.setOnClickListener(v -> {
            if (validateInputs()) {
                sendSignUpRequest();
            }
        });
    }

    private void navigateToLogin() {
        Intent intent = new Intent(SignUpActivity.this, LoginActivity.class);
        startActivity(intent);
    }

    private boolean validateInputs() {
        // Get input values
        String fName = firstName.getText().toString().trim();
        String lName = lastName.getText().toString().trim();
        String userEmail = email.getText().toString().trim();
        String phone = phoneNumber.getText().toString().trim();
        String pass = password.getText().toString().trim();
        String confirmPass = confirmPassword.getText().toString().trim();

        // Validate first name
        if (TextUtils.isEmpty(fName)) {
            firstName.setError("First name is required");
            firstName.requestFocus();
            return false;
        }

        // Validate last name
        if (TextUtils.isEmpty(lName)) {
            lastName.setError("Last name is required");
            lastName.requestFocus();
            return false;
        }

        // Validate email
        if (TextUtils.isEmpty(userEmail) || !Patterns.EMAIL_ADDRESS.matcher(userEmail).matches()) {
            email.setError("Enter a valid email address");
            email.requestFocus();
            return false;
        }

        // Validate phone number
        if (TextUtils.isEmpty(phone)) {
            phoneNumber.setError("Phone number is required");
            phoneNumber.requestFocus();
            return false;
        }

        // Optional: Validate phone number format
        if (!Patterns.PHONE.matcher(phone).matches() || phone.length() < 10) {
            phoneNumber.setError("Enter a valid phone number");
            phoneNumber.requestFocus();
            return false;
        }

        // Validate password
        if (TextUtils.isEmpty(pass)) {
            password.setError("Password is required");
            password.requestFocus();
            return false;
        }

        // Optional: Validate password strength
        if (pass.length() < 6) {
            password.setError("Password must be at least 6 characters");
            password.requestFocus();
            return false;
        }

        // Validate confirm password
        if (!pass.equals(confirmPass)) {
            confirmPassword.setError("Passwords do not match");
            confirmPassword.requestFocus();
            return false;
        }

        // Validate terms and conditions
        if (!termsAndCondition.isChecked()) {
            Toast.makeText(this, "You must agree to the Terms and Conditions", Toast.LENGTH_SHORT).show();
            return false;
        }

        return true; // All validations passed
    }

    private void sendSignUpRequest() {
        // Define the URL for your server endpoint
        String url = "http://10.0.2.2/job_portal_java/register_users.php";

        // Create a StringRequest
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                response -> {
                    // Log the successful response
                    Log.d("SignUpResponse", "Response: " + response);

                    // Handle successful response
                    try {
                        JSONObject jsonResponse = new JSONObject(response);
                        String message = jsonResponse.getString("message");
                        Toast.makeText(SignUpActivity.this, message, Toast.LENGTH_LONG).show();
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(SignUpActivity.this, "Response Parsing Error", Toast.LENGTH_LONG).show();
                    }

                    Intent intent = new Intent(SignUpActivity.this, LoginActivity.class);
                    startActivity(intent);
                    finish();
                },
                error -> {
                    // Log the error
                    Log.d("SignUpError", "Error: " + error.getMessage());

                    // Handle error response
                    Toast.makeText(SignUpActivity.this, "Error: " + error.getMessage(), Toast.LENGTH_LONG).show();
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("firstname", firstName.getText().toString().trim());
                params.put("lastname", lastName.getText().toString().trim());
                params.put("email", email.getText().toString().trim());
                params.put("phone", phoneNumber.getText().toString().trim());
                params.put("password", password.getText().toString().trim());
                return params;
            }
        };

        // Add the request to the RequestQueue
        requestQueue.add(stringRequest);
    }

}
