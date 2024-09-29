package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

public class TipsDetailActicity extends AppCompatActivity {
    ImageView backToHome;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.tips_detail_activity);
        backToHome = findViewById(R.id.backToHome);

//        Back to the tip activity
        backToHome.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(TipsDetailActicity.this, TipsActivity.class);
                startActivity(intent);
            }
        });

    }
}
