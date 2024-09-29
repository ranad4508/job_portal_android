package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class ApplicationDetailActivity extends AppCompatActivity {
    ImageView backToApplication;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.application_item_detail);
        backToApplication = findViewById(R.id.backToApplication);
        backToApplication.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(ApplicationDetailActivity.this, ApplicationActivity.class);
                startActivity(intent);
            }
        });
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation);
        bottomNav.setSelectedItemId(R.id.navigation_application); // Set the correct item as selected
        bottomNav.setOnItemSelectedListener(navListener);
    }

    private final BottomNavigationView.OnItemSelectedListener navListener =
            new BottomNavigationView.OnItemSelectedListener() {


                @Override
                public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                    int itemId = item.getItemId();
                    if (itemId == R.id.navigation_home) {
                        startActivity(new Intent(ApplicationDetailActivity.this, HomeActivity.class));
                        return true;
                    } else if (itemId == R.id.navigation_application) {
                        // Already on applications, do nothing
                        return true;
                    } else if (itemId == R.id.navigation_bookmark) {
                        startActivity(new Intent(ApplicationDetailActivity.this, SavedJobsAvtivity.class));
                        return true;
                    } else if (itemId == R.id.navigation_profile) {
                        startActivity(new Intent(ApplicationDetailActivity.this, ProfileActivity.class));
                        return true;
                    }
                    return false;
                }
            };
}