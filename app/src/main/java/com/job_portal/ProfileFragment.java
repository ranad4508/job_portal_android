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

import org.json.JSONException;
import org.json.JSONObject;

public class ProfileFragment extends Fragment {
    private static final String TAG = "ProfileFragment";
    private static final String USER_DATA_URL = "http://10.0.2.2/job_portal_java/select_users.php"; // Replace with your PHP endpoint

    ImageView editProfileImage;
    ImageView profileImageView;
//    Button updateProfile;
    Button btnLogout;
    TextView etFullname, etEmail, etPhone, etAddress, etOccupation, etDateOfBirth;

    private RequestQueue requestQueue;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.profile_fragment_layout, container, false);

        // Initialize views
        editProfileImage = view.findViewById(R.id.edit_profile_image);
        profileImageView = view.findViewById(R.id.profile_image);
//        updateProfile = view.findViewById(R.id.btn_update);
        btnLogout = view.findViewById(R.id.btn_logout);
        etFullname = view.findViewById(R.id.fullname);
        etEmail = view.findViewById(R.id.et_email);
        etPhone = view.findViewById(R.id.et_phone);
        etAddress = view.findViewById(R.id.et_address);
        etOccupation = view.findViewById(R.id.et_occupation);
        etDateOfBirth = view.findViewById(R.id.et_dob);

        requestQueue = Volley.newRequestQueue(requireContext());
        fetchUserData();

        editProfileImage.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), EditProfileActivity.class);
                startActivity(intent);
            }
        });

        btnLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                clearSession();
                Intent intent = new Intent(getActivity(), LoginActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                startActivity(intent);
                getActivity().finish();
            }
        });

        return view;
    }

    private void fetchUserData() {
        SharedPreferences prefs = getActivity().getSharedPreferences("JobPortalPrefs", Context.MODE_PRIVATE);
        int userId = prefs.getInt("userId", -1); // Default to -1 if userId is not found

        if (userId == -1) {
            Log.e(TAG, "User ID is missing or invalid in SharedPreferences");
            Toast.makeText(getActivity(), "User ID is missing. Please log in again.", Toast.LENGTH_LONG).show();
            return;
        }

        // Convert the integer userId to String for the URL
        String url = USER_DATA_URL + "?user_id=" + userId;

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            JSONObject user = response.getJSONObject("user");

                            // Update views with user data
                            etFullname.setText(user.getString("firstname") +" "+ user.getString("lastname"));
                            etEmail.setText(user.getString("email"));
                            etPhone.setText(user.getString("phone"));
                            etAddress.setText(user.getString("address"));
                            etOccupation.setText(user.getString("occupation"));
                            etDateOfBirth.setText(user.getString("date_of_birth"));

                            String userImage = user.optString("user_image", null); // Use optString() for safety
                            if (userImage != null && !userImage.isEmpty()) {
                                String imageUrl = "http://10.0.2.2/job_portal_java/Admin/" + userImage;

                                Picasso.get()
                                        .load(imageUrl)
                                        .transform(new CircleTransform()) // Apply the custom circle transformation
                                        .placeholder(R.mipmap.account)
                                        .error(R.drawable.error_icon)
                                        .into(profileImageView, new com.squareup.picasso.Callback() {
                                            @Override
                                            public void onSuccess() {
                                                Log.d(TAG, "Image loaded successfully.");
                                                profileImageView.setTag(imageUrl); // Set image URL as tag
                                            }

                                            @Override
                                            public void onError(Exception e) {
                                                Log.e(TAG, "Error loading image: " + e.getMessage());
                                                profileImageView.setImageResource(R.mipmap.account); // Default image on error
                                            }
                                        });
                            } else {
                                profileImageView.setImageResource(R.mipmap.account); // Default image
                                profileImageView.setTag(""); // Clear tag
                            }

                            Log.d(TAG, "Image URL: " + "http://10.0.2.2/job_portal_java/Admin/" + userImage);




                        } catch (JSONException e) {
                            Log.e(TAG, "JSON parsing error: " + e.getMessage());
                            Toast.makeText(getActivity(), "Error parsing user data. Please try again.", Toast.LENGTH_LONG).show();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Error fetching user data: " + error.getMessage());
                Toast.makeText(getActivity(), "Error fetching user data. Please check your network connection.", Toast.LENGTH_LONG).show();
            }
        });

        requestQueue.add(jsonObjectRequest);
    }


    private void clearSession() {
        SharedPreferences prefs = getActivity().getSharedPreferences("JobPortalPrefs", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = prefs.edit();
        editor.remove("isLoggedIn");
        editor.remove("userId");
        editor.remove("email");
        editor.apply();
        Log.d(TAG, "Session cleared");
    }
}
