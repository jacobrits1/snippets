//
//  ViewController.m
//  LetsPrayIOS
//
//  Created by Jaco Brits on 2018/01/12.
//  Copyright © 2018 Mobilee. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    self.webView.delegate = self;
    NSString *url = @"https://letspray.medmin.co.za/client/index.html";
    NSURL *URL = [NSURL URLWithString:url];
    NSURLRequest *requestURL = [NSURLRequest requestWithURL:URL];
    [self.webView loadRequest:requestURL];
    
}

-(void)webViewDidStartLoad:(UIWebView *)webView{
    [self.activityindicator startAnimating];
}

-(void)webViewDidFinishLoad:(UIWebView *)webView{
    [self.activityindicator stopAnimating];
    self.activityindicator.hidesWhenStopped = YES;
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
