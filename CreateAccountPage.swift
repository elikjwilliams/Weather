//
//  CreateAccountPage.swift
//  Professional
//
//  Created by Justin Williams on 5/5/17.
//  Copyright © 2017 Justin Williams. All rights reserved.
//

import UIKit

class CreateAccountPage: UIViewController {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    @IBOutlet weak var email: UITextField!
    @IBOutlet weak var firstName: UITextField!
    @IBOutlet weak var lastName: UITextField!
    
    @IBAction func haveAccountButtonPressed(_ sender: UIButton) {
        clearKeyboard()
    }
    @IBAction func createButtonPressed(_ sender: UIButton) {
        clearKeyboard()
        
        if username.text!.isEmpty || password!.text!.isEmpty || email.text!.isEmpty || firstName!.text!.isEmpty || lastName!.text!.isEmpty{
            username.attributedPlaceholder = NSAttributedString(string: "username", attributes: [NSForegroundColorAttributeName: UIColor.red])
            password.attributedPlaceholder = NSAttributedString(string: "password", attributes: [NSForegroundColorAttributeName: UIColor.red])
            email.attributedPlaceholder = NSAttributedString(string: "email", attributes: [NSForegroundColorAttributeName: UIColor.red])
            firstName.attributedPlaceholder = NSAttributedString(string: "first name", attributes: [NSForegroundColorAttributeName: UIColor.red])
            lastName.attributedPlaceholder = NSAttributedString(string: "last name", attributes: [NSForegroundColorAttributeName: UIColor.red])
        }
            //create user in db
        else{
            
            //url to php file
            let url = URL(string: "http://localhost/playaround/register.php")
            
            //request this file
            //let request = NSMutableURLRequest(url: url as URL)
            var request = URLRequest(url: url!)
            
            
            //method to pass data to this file
            request.httpMethod = "POST"
            
            //body to be apended to url
            let body = "username= \(username.text!)&password=\(password.text!)&email=\(email.text!.lowercased())&fullname=\(firstName.text!)%20\(lastName.text!)"
            request.httpBody = body.data(using: String.Encoding.utf8)
            
            URLSession.shared.dataTask(with: request, completionHandler: { (data, response, error) in
                
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
                            print("Username and/or Email NOT associated with account")
                            self.username.attributedPlaceholder = NSAttributedString(string: "username", attributes: [NSForegroundColorAttributeName: UIColor.red])
                            self.email.attributedPlaceholder = NSAttributedString(string: "email", attributes: [NSForegroundColorAttributeName: UIColor.red])
                            
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
        self.email.resignFirstResponder()
        self.firstName.resignFirstResponder()
        self.lastName.resignFirstResponder()
    }
    
    
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
