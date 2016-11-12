package com.jinson.irins.singlynews;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.ImageView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Arrays;
import java.util.HashMap;
import java.util.List;

public class StartActivity extends AppCompatActivity {



    JSONArray jsonarray;
    public static int size=0;
    public static String categories="";
    Button b1;
    //    private static final String REGISTER_URL = "http://personalfinancemanager.in/msgandroid/Phnmessage/allreceive";
    //private static final String REGISTER_URL = "http://personalfinancemanager.in/api/news";
    private static final String REGISTER_URL = "http://ec2-35-154-31-121.ap-south-1.compute.amazonaws.com/mmapi.php";
    ImageView image;
    Animation slide1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_start);
        image= (ImageView) findViewById(R.id.image);
        slide1= AnimationUtils.loadAnimation(getApplicationContext(), R.anim.rotation_anim);
       // image.setAnimation(slide1);
        registerUser();




    }


    private void registerUser() {
//        String receive ="receive";
        String receive ="";
        register(receive);
    }
    private void register(String recv) {
        class RegisterUser extends AsyncTask<String, Void, String> {
            ProgressDialog loading;
            RegisterUserClass ruc = new RegisterUserClass();

            @Override
            protected void onPreExecute() {
                super.onPreExecute();
                //loading = ProgressDialog.show(StartActivity.this, "Please Wait",null, true, true);
            }

            @Override
            protected void onPostExecute(String s) {
                super.onPostExecute(s);
                //loading.dismiss();
                Log.i("SucceSs", s);

                new Handler().postDelayed(new Runnable() {
                    @Override
                    public void run() {

                        Intent i=new Intent(getApplicationContext(),OutsideTab.class);
                        startActivity(i);
                        finish();
                    }
                }, 3500);


                MainActivity.message=s;

                // jsonparser(s);
                //categoryselect(categories);
            }

            @Override
            protected String doInBackground(String... params) {

                HashMap<String, String> data = new HashMap<String,String>();
                data.put("receive",params[0]);
                String result = ruc.sendPostRequest(REGISTER_URL,data);
                return  result;
            }
        }

        RegisterUser ru = new RegisterUser();
        ru.execute(recv);
    }
    private void jsonparser(String s) {


        if (s != null) {
            try {
                jsonarray = new JSONArray(s);
                //totalsms=jsonarray.length();
                int j=0;
                //k=0;
                for (int i = 0; i < jsonarray.length(); i++) {
                    JSONObject jsonobject = jsonarray.getJSONObject(i);

                    String sender = jsonobject.getString("category");

                    if(!categories.contains(sender)) {
                        categories=categories.concat(String.valueOf(sender)+"/");
                        size++;
                        //Log.w("Sender",categories);
                        MainActivity.totalpages=size;
                    }


                }

            } catch (JSONException e) {
                e.printStackTrace();
            }
        } else {
            Log.e("ServiceHandler", "Couldn't get any data from the url");
        }


    }
    public void categoryselect(String categories){

        List<String> catList = Arrays.asList(categories.split("/"));
        MainActivity.totalpages=size;
        for(int i=0;i<size;i++){
            String category=catList.get(i);
            //Log.w("Category",category);
            MainActivity.titles[i]=category;
        }

        Intent i=new Intent(getApplicationContext(),OutsideTab.class);
        startActivity(i);
        finish();

    }
}
