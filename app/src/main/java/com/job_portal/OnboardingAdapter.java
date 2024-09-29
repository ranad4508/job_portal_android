package com.job_portal;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;

public class OnboardingAdapter extends FragmentStateAdapter {

    private final String[] titles = {"Welcome", "Discover Jobs", "Apply Easily"};
    private final String[] descriptions = {
            "Welcome to our Job Sathi.",
            "Find the best jobs that match your skills.",
            "Apply for jobs with just a few clicks."
    };
    private final int[] images = {
            R.mipmap.welcome,  // Assuming welcome_gif is a GIF file in res/drawable
            R.mipmap.choose_candidate,
            R.mipmap.sign_contract,
    };

    public OnboardingAdapter(@NonNull FragmentActivity fragmentActivity) {
        super(fragmentActivity);
    }

    @NonNull
    @Override
    public Fragment createFragment(int position) {
        return OnboardingFragment.newInstance(titles[position], descriptions[position], images[position]);
    }

    @Override
    public int getItemCount() {
        return titles.length;
    }
}
