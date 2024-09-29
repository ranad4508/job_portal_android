package com.job_portal;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

public class SavedJobsFragment extends Fragment {
    LinearLayout savedJobItems;
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.saved_jobs_fragment_layout,null);
        savedJobItems = view. findViewById(R.id.savedJobItems);
        savedJobItems.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), SavedJobsAvtivity.class);
                startActivity(intent);
            }
        });
        return view;
    }

}
