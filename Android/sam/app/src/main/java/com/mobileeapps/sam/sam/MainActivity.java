package com.mobileeapps.sam.sam;

import android.app.ProgressDialog;
import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebStorage;
import android.webkit.WebView;

public class MainActivity extends AppCompatActivity {

    private WebView mWebView;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mWebView = (WebView) findViewById(R.id.webView);
        //WebView webView=new WebView(this);
        mWebView.setInitialScale(1);
        mWebView.requestFocus(View.FOCUS_DOWN);
        mWebView.requestFocusFromTouch();

        Object database;
        final String databasePath = this.getApplicationContext().getDir(“database”, Context.MODE_PRIVATE).getPath();
        WebSettings settings = mWebView.getSettings();
        settings.setDatabasePath(databasePath);
        settings.setAppCacheMaxSize(5*1024*1024);
        settings.setJavaScriptEnabled(true);
        settings.setJavaScriptCanOpenWindowsAutomatically(true);
        settings.setDomStorageEnabled(true);
        settings.setDatabaseEnabled(true);
        settings.setLoadsImagesAutomatically(true);
        settings.setUseWideViewPort(true);
        settings.setLoadsImagesAutomatically(true);
        settings.setAppCacheEnabled(true);
        settings.setCacheMode(WebSettings.LOAD_NO_CACHE);
        settings.setRenderPriority(WebSettings.RenderPriority.HIGH);
        settings.setAllowFileAccess(false);
        settings.setUseWideViewPort(false);
        settings.setSupportZoom(false);
        settings.setSavePassword(false);
        settings.setSupportMultipleWindows(false);

        mWebView.setWebChromeClient(new WebChromeClient()
        {
            @Override
            public void onProgressChanged(WebView view, int newProgress) {
                super.onProgressChanged(view, newProgress);
            }

            @Override
            public void onExceededDatabaseQuota(String url,
                                                String databaseIdentifier, long currentQuota,
                                                long estimatedSize, long totalUsedQuota,
                                                WebStorage.QuotaUpdater quotaUpdater) {
                quotaUpdater.updateQuota(5 * 1024 * 1024);
            }
        });

// Above lines are necessary to load javascript and allowing your webview to create sqlite database so KEEP THEM AS IT IS.
        setContentView(mWebView);
        mWebView.loadUrl("file:///android_asset/index.html");
    }

}
}
