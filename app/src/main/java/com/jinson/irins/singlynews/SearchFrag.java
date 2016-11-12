package com.jinson.irins.singlynews;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.StaggeredGridLayoutManager;
import android.util.Log;
import android.util.TypedValue;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;


public class SearchFrag extends Fragment {
    Button bsearch;
    public static String searchitem;
    EditText editsearch;
    public ImageView imageload;
    public TextView notfound;
    public SearchAdapter adapter;
    public List<Search> albumList;
    private static final String REGISTER_URL = "http://ec2-35-154-31-121.ap-south-1.compute.amazonaws.com/searchsingly.php";
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.layout_search, container, false);
        bsearch= (Button) v.findViewById(R.id.buttonsearch);
        editsearch= (EditText) v.findViewById(R.id.editsearch);
        notfound= (TextView) v.findViewById(R.id.notfound);
        imageload= (ImageView) v.findViewById(R.id.load);


        RecyclerView recyclerView = (RecyclerView) v.findViewById(R.id.recycler_view);
        albumList = new ArrayList<>();
        adapter = new SearchAdapter(getContext(), albumList);
        Resources r = getResources();
        RecyclerView.LayoutManager mLayoutManager = new StaggeredGridLayoutManager(1, StaggeredGridLayoutManager.VERTICAL);
        recyclerView.setLayoutManager(mLayoutManager);
//            Resources r = getResources();
        recyclerView.addItemDecoration(new MainActivity.GridSpacingItemDecoration(2, Math.round(TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 10, r.getDisplayMetrics())), true));
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.setAdapter(adapter);



        bsearch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (editsearch.getText().toString().length() > 0) {
                    searchitem = editsearch.getText().toString();
                    registerUser();
                }

            }
        });

      editsearch.setOnKeyListener(new View.OnKeyListener() {
          @Override
          public boolean onKey(View v, int keyCode, KeyEvent event) {

              if ((event.getAction() == KeyEvent.ACTION_DOWN) &&
                      (keyCode == KeyEvent.KEYCODE_ENTER)) {
                  searchitem = editsearch.getText().toString();
                  registerUser();
                  return true;
              }
              return false;
          }
      });
        return v;


    }
    private void registerUser() {

        String receive =searchitem;
        register(receive);
    }
    private void register(String recv) {
        class RegisterUser extends AsyncTask<String, Void, String> {

            RegisterUserClass ruc = new RegisterUserClass();

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                imageload.setVisibility(View.VISIBLE);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                Log.i("SucceSs", s);
                imageload.setVisibility(View.GONE);
                jsonparsing(s);


            }

            @Override
            protected String doInBackground(String... params) {

                HashMap<String, String> data = new HashMap<String,String>();
                data.put("search",params[0]);
                String result = ruc.sendPostRequest(REGISTER_URL,data);
                return  result;
            }
        }

        RegisterUser ru = new RegisterUser();
        ru.execute(recv);
    }

    public void jsonparsing(String message){
        JSONArray jsonarray;
        albumList.clear();

        try {
            jsonarray = new JSONArray(message);
            int j=0;
            for (int i = 0; i < jsonarray.length(); i++) {
                JSONObject jsonobject = jsonarray.getJSONObject(i);
                //String category=jsonobject.getString("category");
                String title = jsonobject.getString("title");
                String news = jsonobject.getString("description");
                String site = jsonobject.getString("site");
                String imageurl=jsonobject.getString("image");
//                    if(category.contains(MainActivity.titles[MainActivity.posi])) {

                if (title.length()>0&&message.length()>0) {
                    Search a = new Search(title, news, site,imageurl);
                    albumList.add(a);
                    adapter.notifyDataSetChanged();

                }
                // }

            }


        } catch (JSONException e) {
            e.printStackTrace();
        }
    }


}
