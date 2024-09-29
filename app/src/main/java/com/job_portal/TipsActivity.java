package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

public class TipsActivity extends AppCompatActivity {

    Button readTipsDetail;
    ImageView backToHome;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.tips_all_activity);


        readTipsDetail = findViewById(R.id.readTipsDetail);
        backToHome = findViewById(R.id.backToHome);
//        read tips detail

        readTipsDetail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(TipsActivity.this, TipsDetailActicity.class);
                startActivity(intent);
            }
        });

        backToHome.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(TipsActivity.this, HomeActivity.class);
                startActivity(intent);
            }
        });
    }
}
