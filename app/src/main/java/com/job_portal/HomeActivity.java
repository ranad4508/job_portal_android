package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class HomeActivity extends AppCompatActivity {
    TextView seeAllJobs;
    ImageView appLogo;
    TextView seeAllTips;
    Button readMoreTips;
    ImageView notificationIcon;
    LinearLayout jobItem;

    LinearLayout home, applications;


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.home_page_layout);

        home = findViewById(R.id.home);
        applications = findViewById(R.id.applications);
        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.container, new HomeFragment()).commit();

    }

    public void tabClickListener(View view){
        if(view.getId()==R.id.home){
            getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.container, new HomeFragment()).commit();
        }else if(view.getId()==R.id.applications){
            getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.container, new ApplicationFragment()).commit();
        }else if(view.getId()==R.id.savedJobs){
            getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.container, new SavedJobsFragment()).commit();
        }else if(view.getId()==R.id.profile){
            getSupportFragmentManager()
                    .beginTransaction()
                    .replace(R.id.container, new ProfileFragment()).commit();
        }
    }

}