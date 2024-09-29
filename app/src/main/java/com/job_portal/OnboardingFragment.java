package com.job_portal;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

public class OnboardingFragment extends Fragment {

    private static final String ARG_TITLE = "arg_title";
    private static final String ARG_DESCRIPTION = "arg_description";
    private static final String ARG_IMAGE_RES_ID = "arg_image_res_id";

    public static OnboardingFragment newInstance(String title, String description, int imageResId) {
        OnboardingFragment fragment = new OnboardingFragment();
        Bundle args = new Bundle();
        args.putString(ARG_TITLE, title);
        args.putString(ARG_DESCRIPTION, description);
        args.putInt(ARG_IMAGE_RES_ID, imageResId);
        fragment.setArguments(args);
        return fragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_onboarding, container, false);

        TextView titleTextView = view.findViewById(R.id.onboardingTitle);
        TextView descriptionTextView = view.findViewById(R.id.onboardingDescription);
        ImageView imageView = view.findViewById(R.id.onboardingImage);

        Bundle args = getArguments();
        if (args != null) {
            titleTextView.setText(args.getString(ARG_TITLE));
            descriptionTextView.setText(args.getString(ARG_DESCRIPTION));
            int imageResId = args.getInt(ARG_IMAGE_RES_ID);
            if (imageView != null) {
                imageView.setImageResource(imageResId);
            }
        }

        return view;
    }
}
