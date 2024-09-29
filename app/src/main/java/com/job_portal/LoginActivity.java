package com.job_portal;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

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

public class LoginActivity extends AppCompatActivity {
    private EditText emailEditText, passwordEditText;
    private Button signInButton;
    private TextView createAccount, forgotPassword;
    private CheckBox rememberMeCheckBox;
    private ProgressBar loadingSpinner;
    private RequestQueue requestQueue;


    // SharedPreferences constants
    private static final String PREFS_NAME = "JobPortalPrefs";
    private static final String PREF_REMEMBER_ME = "rememberMe";
    private static final String PREF_EMAIL = "email";
    private static final String PREF_IS_LOGGED_IN = "isLoggedIn"; // Session flag
    private static final String PREF_USER_ID = "userId"; // For storing user ID if needed

    // URL for login request (replace with your server URL)
    String loginUrl = "http://10.0.2.2/job_portal_java/login_user.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Check if user is already logged in
        SharedPreferences preferences = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        boolean isLoggedIn = preferences.getBoolean(PREF_IS_LOGGED_IN, false);
        if (isLoggedIn) {
            // Redirect to home if already logged in
            Intent intent = new Intent(LoginActivity.this, HomeActivity.class);
            startActivity(intent);
            finish(); // Close LoginActivity
        }

        setContentView(R.layout.login_activity);

        // Initialize views
        emailEditText = findViewById(R.id.email);
        passwordEditText = findViewById(R.id.password);
        signInButton = findViewById(R.id.signInButton);
        createAccount = findViewById(R.id.createAccount);
        rememberMeCheckBox = findViewById(R.id.rememberMe);
        loadingSpinner = findViewById(R.id.loadingSpinner);
        forgotPassword = findViewById(R.id.forgotPassword);
        forgotPassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(LoginActivity.this, ForgotPasswordActivity.class);
                startActivity(intent);
            }
        });

        // Initialize RequestQueue for network operations
        requestQueue = Volley.newRequestQueue(this);

        // Retrieve saved preferences for Remember Me functionality
        boolean rememberMe = preferences.getBoolean(PREF_REMEMBER_ME, false);
        rememberMeCheckBox.setChecked(rememberMe);
        if (rememberMe) {
            String savedEmail = preferences.getString(PREF_EMAIL, "");
            emailEditText.setText(savedEmail);
        }

        // Set click listener for sign-in button
        signInButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (validateInputs()) {
                    String email = emailEditText.getText().toString().trim();
                    String password = passwordEditText.getText().toString().trim();
                    loginUser(email, password);
                }
            }
        });

        // Set click listener for create account link
        createAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(LoginActivity.this, SignUpActivity.class);
                startActivity(intent);
            }
        });
    }

    // Validate input fields (email and password)
    private boolean validateInputs() {
        String email = emailEditText.getText().toString().trim();
        String password = passwordEditText.getText().toString().trim();

        // Validate email
        if (TextUtils.isEmpty(email) || !Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            emailEditText.setError("Enter a valid email address");
            emailEditText.requestFocus();
            return false;
        }

        // Validate password
        if (TextUtils.isEmpty(password)) {
            passwordEditText.setError("Password is required");
            passwordEditText.requestFocus();
            return false;
        }

        return true; // All validations passed
    }

    // Perform login operation with provided email and password
    private void loginUser(final String email, final String password) {
        loadingSpinner.setVisibility(View.VISIBLE); // Show loading spinner

        StringRequest stringRequest = new StringRequest(Request.Method.POST, loginUrl,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        loadingSpinner.setVisibility(View.GONE); // Hide loading spinner
                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            boolean success = jsonObject.getBoolean("success");
                            String message = jsonObject.getString("message");

                            if (success) {
                                // Extract user ID from response if needed
                                int userId = jsonObject.getInt("user_id");

                                // Handle successful login
                                Toast.makeText(LoginActivity.this, "Login successful", Toast.LENGTH_SHORT).show();
                                savePreferences(email, userId); // Save login data

                                // Proceed to home screen (e.g., HomeActivity)
                                Intent intent = new Intent(LoginActivity.this, HomeActivity.class);
                                startActivity(intent);
                                finish(); // Close login activity
                            } else {
                                // Handle failed login
                                Toast.makeText(LoginActivity.this, message, Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(LoginActivity.this, "Error parsing response", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        loadingSpinner.setVisibility(View.GONE); // Hide loading spinner
                        Toast.makeText(LoginActivity.this, "Error: " + error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                // Pass parameters to the login API (email and password)
                Map<String, String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", password);
                return params;
            }
        };

        // Add the request to the RequestQueue
        requestQueue.add(stringRequest);
    }

    // Save preferences (session data, remember me, etc.)
    private void savePreferences(String email, int userId) {
        SharedPreferences preferences = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();

        // Handle "Remember Me" checkbox functionality
        if (rememberMeCheckBox.isChecked()) {
            editor.putBoolean(PREF_REMEMBER_ME, true);
            editor.putString(PREF_EMAIL, email);
        } else {
            editor.putBoolean(PREF_REMEMBER_ME, false);
            editor.remove(PREF_EMAIL);
        }

        // Save session status and user ID
        editor.putBoolean(PREF_IS_LOGGED_IN, true); // User is logged in
        editor.putInt(PREF_USER_ID, userId); // Save user ID
        editor.apply(); // Apply changes to SharedPreferences
    }
}
