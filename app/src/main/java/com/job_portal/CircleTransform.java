
package com.job_portal;
import android.graphics.Bitmap;
import android.graphics.BitmapShader;
import android.graphics.Canvas;
import android.graphics.Paint;
import com.squareup.picasso.Transformation;

public class CircleTransform implements Transformation {

    @Override
    public Bitmap transform(Bitmap source) {
        // Calculate the size of the circle
        int size = Math.min(source.getWidth(), source.getHeight());

        // Calculate the center of the image
        int x = (source.getWidth() - size) / 2;
        int y = (source.getHeight() - size) / 2;

        // Create a squared bitmap
        Bitmap squaredBitmap = Bitmap.createBitmap(source, x, y, size, size);
        if (squaredBitmap != source) {
            source.recycle();
        }

        // Create a circular bitmap
        Bitmap bitmap = Bitmap.createBitmap(size, size, source.getConfig());
        Canvas canvas = new Canvas(bitmap);
        Paint paint = new Paint();
        BitmapShader shader = new BitmapShader(squaredBitmap, BitmapShader.TileMode.CLAMP, BitmapShader.TileMode.CLAMP);
        paint.setShader(shader);
        paint.setAntiAlias(true);

        // Draw a circle on the canvas
        float radius = size / 2f;
        canvas.drawCircle(radius, radius, radius, paint);

        squaredBitmap.recycle();
        return bitmap;
    }

    @Override
    public String key() {
        return "circle";
    }
}
