package com.jinson.irins.singlynews;

import android.content.res.Resources;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.StaggeredGridLayoutManager;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by HP on 10-11-2016.
 */
public class AllNews extends Fragment {

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        AlbumsAdapter adapter;
        List<Album> albumList;
        View rootView = inflater.inflate(R.layout.allnewstab, container, false);

        RecyclerView recyclerView = (RecyclerView) rootView.findViewById(R.id.recycler_view);
        albumList = new ArrayList<>();
        adapter = new AlbumsAdapter(getContext(), albumList);
        Resources r = getResources();
        RecyclerView.LayoutManager mLayoutManager = new StaggeredGridLayoutManager(1, StaggeredGridLayoutManager.VERTICAL);
        recyclerView.setLayoutManager(mLayoutManager);
//            Resources r = getResources();
        recyclerView.addItemDecoration(new MainActivity.GridSpacingItemDecoration(2, Math.round(TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 10, r.getDisplayMetrics())), true));
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.setAdapter(adapter);
        JSONArray jsonarray;
        int k=0;
        if (MainActivity.message != null) {
            try {
                jsonarray = new JSONArray(MainActivity.message);
                int j=0;
                for (int i = 0; i < jsonarray.length(); i++) {
                    JSONObject jsonobject = jsonarray.getJSONObject(i);
                    //String category=jsonobject.getString("category");
                    String sender = jsonobject.getString("title");
                    String message = jsonobject.getString("summary");
//                    if(category.contains(MainActivity.titles[MainActivity.posi])) {

                    if (sender.length()>0&&message.length()>0) {
                        Album a = new Album(sender, message, "sss");
                        albumList.add(a);
                        adapter.notifyDataSetChanged();
                        k++;
                    }
                   // }

                }


            } catch (JSONException e) {
                e.printStackTrace();
            }
        }


        return rootView;

    }
}
