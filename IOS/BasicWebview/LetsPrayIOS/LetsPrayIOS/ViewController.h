//
//  ViewController.h
//  LetsPrayIOS
//
//  Created by Jaco Brits on 2018/01/12.
//  Copyright © 2018 Mobilee. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController <UIWebViewDelegate>
@property (weak, nonatomic) IBOutlet UIWebView *webView;
@property (weak, nonatomic) IBOutlet UIActivityIndicatorView *activityindicator;


@end

