package com.jinson.irins.singlynews;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by HP on 20-10-2016.
 */
public class DbHandler extends SQLiteOpenHelper {
    private static final int DATABASE_VERSION = 1;
    private static final String DATABASE_NAME = "IRINS";
    private static final String TABLE_NEWS = "saved";



    private static final String TITLE = "title";
    private static final String CONTENT = "content";



    public DbHandler(Context context)
    {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }


    @Override
    public void onCreate(SQLiteDatabase db) {
        String CREATE_CONTACTS_TABLE= "CREATE TABLE IF NOT EXISTS " + TABLE_NEWS + "(" + TITLE + " TEXT," + CONTENT + " TEXT" + ")";
        db.execSQL(CREATE_CONTACTS_TABLE);

    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS" + TABLE_NEWS);
        onCreate(db);
    }

    void addSaved(Saved save)
    {
        SQLiteDatabase db=this.getWritableDatabase();
        ContentValues values = new ContentValues();

        values.put(TITLE, save.getTitle());
        values.put(CONTENT, save.getContent());

        db.insert(TABLE_NEWS,null,values);
        db.close();
    }



    public List<Saved> getAllContacts()
    {
        List<Saved>contactList=new ArrayList<Saved>();
        String selectQuery="SELECT * FROM " +TABLE_NEWS;
        SQLiteDatabase db=this.getWritableDatabase();
        Cursor cursor=db.rawQuery(selectQuery,null);
        if (cursor.moveToFirst())
        {
            do {
                Saved contact = new Saved();
                contact.setTitle(cursor.getString(0));
                contact.setContent(cursor.getString(1));
                // contact.setPh(cursor.getString(2));
                contactList.add(contact);
            } while (cursor.moveToNext());
        }

        return contactList;
    }






    public int getContactsCount()
    {
        String countQuery = "SELECT * FROM " + TABLE_NEWS;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(countQuery, null);
        cursor.close();
        return cursor.getCount();
    }


}
