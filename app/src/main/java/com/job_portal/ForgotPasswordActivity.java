package com.job_portal;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class ForgotPasswordActivity extends AppCompatActivity {
    private TextView emailField, passwordField, confirmPasswordField, successMessage;
    private Button signInButton;
    private ProgressBar loadingSpinner;

    private static final String FORGOT_PASSWORD_URL = "http://10.0.2.2/job_portal_java/forgot_password.php";

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.forgot_password_layout);

        // Initialize Views
        emailField = findViewById(R.id.email);
        passwordField = findViewById(R.id.password);
        confirmPasswordField = findViewById(R.id.confirmPassword);
        signInButton = findViewById(R.id.signInButton);
        loadingSpinner = findViewById(R.id.loadingSpinner);
        successMessage = findViewById(R.id.successedMessage);

        signInButton.setOnClickListener(v -> handleForgotPassword());
    }

    private void handleForgotPassword() {
        String email = emailField.getText().toString().trim();
        String password = passwordField.getText().toString().trim();
        String confirmPassword = confirmPasswordField.getText().toString().trim();

        // Validate input
        if (email.isEmpty() || password.isEmpty() || confirmPassword.isEmpty()) {
            Toast.makeText(this, "Please fill all fields", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!password.equals(confirmPassword)) {
            Toast.makeText(this, "Passwords do not match", Toast.LENGTH_SHORT).show();
            return;
        }

        // Show progress spinner
        loadingSpinner.setVisibility(View.VISIBLE);

        // Create JSON request body
        JSONObject requestBody = new JSONObject();
        try {
            requestBody.put("email", email);
            requestBody.put("password", password);
        } catch (JSONException e) {
            e.printStackTrace();
            loadingSpinner.setVisibility(View.GONE);
            Toast.makeText(this, "Error creating request", Toast.LENGTH_SHORT).show();
            return;
        }

        // Create the StringRequest using Volley
        StringRequest stringRequest = new StringRequest(Request.Method.POST, FORGOT_PASSWORD_URL,
                response -> {
                    loadingSpinner.setVisibility(View.GONE);
                    try {
                        JSONObject jsonResponse = new JSONObject(response);
                        if (jsonResponse.getBoolean("success")) {
                            successMessage.setVisibility(View.VISIBLE);
                            successMessage.setText("Password updated successfully!");
                        } else {
                            Toast.makeText(ForgotPasswordActivity.this, jsonResponse.getString("message"), Toast.LENGTH_SHORT).show();
                        }
                    } catch (JSONException e) {
                        Toast.makeText(ForgotPasswordActivity.this, "Response error", Toast.LENGTH_SHORT).show();
                    }
                },
                error -> {
                    loadingSpinner.setVisibility(View.GONE);
                    Toast.makeText(ForgotPasswordActivity.this, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
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
