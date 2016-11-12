package com.jinson.irins.singlynews;

/**
 * Created by HP on 24-10-2016.
 */

import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.speech.tts.TextToSpeech;
import android.support.design.widget.Snackbar;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import java.util.List;
import java.util.Locale;

public class AlbumsAdapter extends RecyclerView.Adapter<AlbumsAdapter.MyViewHolder> {

    private Context mContext;
    public static TextToSpeech t1;
    private List<Album> albumList;
    public String[] urls={"http://oi67.tinypic.com/29msnky.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg","http://oi63.tinypic.com/2pzh9pd.jpg","http://oi67.tinypic.com/263ya7q.jpg"};

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView heading, content;
        public Button speaker,save,share;
        public ImageView image;

        public MyViewHolder(View view) {
            super(view);
            heading = (TextView) view.findViewById(R.id.heading);
            content = (TextView) view.findViewById(R.id.content);
            speaker = (Button) view.findViewById(R.id.speaker);
            save = (Button) view.findViewById(R.id.save);
            image= (ImageView) view.findViewById(R.id.image);
            share= (Button) view.findViewById(R.id.share);
        }
    }


    public AlbumsAdapter(Context mContext, List<Album> albumList) {
        this.mContext = mContext;
        this.albumList = albumList;
    }
    public Context AlbumsAdapter() {

        return mContext;
    }

    @Override
    public MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.album_card, parent, false);

        return new MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(final MyViewHolder holder, int position) {
        final Album album = albumList.get(position);
        holder.heading.setText(album.getHeading());
        holder.content.setText(album.getContent());
//        Picasso.with(mContext).load(urls[position]).placeholder(R.drawable.progress_animation).into(holder.image);

        final DbHandler db1 = new DbHandler(AlbumsAdapter());
        List<Saved> saved = db1.getAllContacts();

        for (int i = 0; i < saved.size(); i++) {
            Log.e("Total number",toString().valueOf(saved.size()));
            if(album.getHeading().equals(saved.get(i).getTitle())&& album.getContent().equals(saved.get(i).getContent())){
                Log.w("Equal", album.getHeading() + "\n" + saved.get(i).getTitle());

                //holder.save.setVisibility(View.GONE);
                //holder.share.setVisibility(View.VISIBLE);
            }
        }
//        if(album.getCat().equals("Saved"))
//            holder.save.setVisibility(View.GONE);

        db1.close();
        t1=new TextToSpeech(mContext, new TextToSpeech.OnInitListener() {
            @Override
            public void onInit(int status) {
                if(status != TextToSpeech.ERROR) {
                    int currentapiVersion = android.os.Build.VERSION.SDK_INT;
                    if(currentapiVersion>=21)
                        t1.setLanguage(Locale.forLanguageTag("hin-IND"));
                    else
                        t1.setLanguage(Locale.ENGLISH);
                    t1.setSpeechRate(0.7f);
                    //t1.setPitch(2);
                }
            }
        });


        // loading album cover using Glide library
       // Glide.with(mContext).load(album.getThumbnail()).into(holder.thumbnail);

        holder.speaker.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //Snackbar.make(v, "Already Added"+album.getContent(), Snackbar.LENGTH_LONG).setAction("No action", null).show();
                String toSpeak=album.getHeading()+"\n\n"+album.getContent();
                //Log.w("Position", toString().valueOf(position));
                if(t1.isSpeaking()){
                    t1.stop();
                }
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    t1.speak(toSpeak, TextToSpeech.QUEUE_FLUSH, null,null);

                } else {
                    t1.speak(toSpeak, TextToSpeech.QUEUE_FLUSH, null);
                }
            }
        });
        holder.save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //Snackbar.make(v, "Already Added"+album.getHeading(), Snackbar.LENGTH_LONG).setAction("No action", null).show();
             //   holder.save.setBackgroundResource(R.drawable.saved);
                final DbHandler db1 = new DbHandler(v.getContext());
                List<Saved> saved = db1.getAllContacts();
                int j = 0;
                for (int i = 0; i < saved.size(); i++) {
                    if (album.getHeading().equals(saved.get(i).getTitle()) && album.getContent().equals(saved.get(i).getContent())) {
                        j = 1;
                    }

                }
                db1.close();

                DbHandler db = new DbHandler(v.getContext());
                if (j == 0) {
                    db.addSaved(new Saved(album.getHeading(), album.getContent()));
                    Snackbar.make(v, "Saved", Snackbar.LENGTH_LONG)
                            .setAction("No action", null).show();
                    //viewHolder.save.setTag(position);
                    // viewHolder.save.setBackgroundResource(R.drawable.saved);
                }
                if (j == 1) {
                    Snackbar.make(v, "Already Added", Snackbar.LENGTH_LONG).setAction("No action", null).show();
                }

                db.close();
            }
        });

        holder.share.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                String sharecontent="*"+album.getHeading()+"*\n"+album.getContent();
//                Intent whatsappIntent = new Intent(Intent.ACTION_SEND);
//                whatsappIntent.setType("text/plain");
//                whatsappIntent.setPackage("com.whatsapp");
//                whatsappIntent.putExtra(Intent.EXTRA_TEXT, sharecontent);
//                try {
//                    mContext.startActivity(whatsappIntent);
//                } catch (android.content.ActivityNotFoundException ex) {
//                    //ToastHelper.MakeShortText("Whatsapp have not been installed.");
//                }

                Intent sendIntent = new Intent();
                sendIntent.setAction(Intent.ACTION_SEND);
                sendIntent.putExtra(Intent.EXTRA_TEXT,sharecontent);
                sendIntent.setType("text/plain");
                Intent.createChooser(sendIntent,"Share via");
                mContext.startActivity(sendIntent);

//                Uri imageUri = Uri.parse("http://2805messa.8.2.f.unblog.fr/files/2008/03/elmahdia.jpg");
//                Intent whatsappIntent = new Intent(android.content.Intent.ACTION_SEND);
//                whatsappIntent.setType("image/*");
//                whatsappIntent.putExtra(Intent.EXTRA_STREAM, imageUri);
//                whatsappIntent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION);//add image path
//                mContext.startActivity(Intent.createChooser(whatsappIntent, "Share image using"));

            }
        });
    }



    @Override
    public int getItemCount() {
        return albumList.size();
    }
}
