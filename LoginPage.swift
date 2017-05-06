//
//  ViewController.swift
//  Professional
//
//  Created by Justin Williams on 5/5/17.
//  Copyright © 2017 Justin Williams. All rights reserved.
//

import UIKit

class LoginPage: UIViewController {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    
    @IBAction func createAccountButtonPressed(_ sender: UIButton) {
        clearKeyboard()
    }
    
    @IBAction func loginButtonPressed(_ sender: UIButton) {
        clearKeyboard()
        if username.text!.isEmpty || password!.text!.isEmpty {
            username.attributedPlaceholder = NSAttributedString(string: "username", attributes: [NSForegroundColorAttributeName: UIColor.red])
            password.attributedPlaceholder = NSAttributedString(string: "password", attributes: [NSForegroundColorAttributeName: UIColor.red])
        }
            //create user in db
        else{
            
            //url to php file
            let url = URL(string: "http://localhost/playaround/login.php")
            
            //request this file
            //let request = NSMutableURLRequest(url: url as URL)
            var request = URLRequest(url: url!)
            
            
            //method to pass data to this file
            request.httpMethod = "POST"
            
            //body to be apended to url
            let body = "username= \(username.text!)&password=\(password.text!)"
            request.httpBody = body.data(using: String.Encoding.utf8)

            
            URLSession.shared.dataTask(with: request, completionHandler: { (data, response, error) in
                
                //print(error.localizedDescription)
                print(response.debugDescription)
                
                if(error == nil){
                    //get main queue in code process to communicate back to ui
                    do{
                        let jsonDict = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                        guard let parseJSON = jsonDict else {
                            print("Error while parsong")
                            return
                        }
                        
                        let id = parseJSON["id"]
                        
                        if(id != nil){
                            print(parseJSON)
                            
                            //select the main story board
                            let storyboard = UIStoryboard(name: "Main", bundle: nil)
                            
                            //set next view to Weatherpage
                            let nextView = storyboard.instantiateViewController(withIdentifier: "WeatherPage")
                            
                            //navigate to the Weatherpage from Create Account Page
                            self.present(nextView, animated: true, completion: nil)
                            
                            
                            
                        }
                        else {
                            print("Username and/or Password is invalid")
                            self.username.attributedPlaceholder = NSAttributedString(string: "username", attributes: [NSForegroundColorAttributeName: UIColor.red])
                            self.password.attributedPlaceholder = NSAttributedString(string: "password", attributes: [NSForegroundColorAttributeName: UIColor.red])
                            
                        }
                    }
                    catch{
                        print(error.localizedDescription)
                    }
                    
                }
            }).resume()
        }
        
    }
    
    func clearKeyboard(){
        self.username.resignFirstResponder()
        self.password.resignFirstResponder()
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}

