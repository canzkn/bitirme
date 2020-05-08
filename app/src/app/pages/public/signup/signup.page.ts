import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastService } from '../../../services/toast/toast.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service'

@Component({
  selector: 'app-signup',
  templateUrl: './signup.page.html',
  styleUrls: ['./signup.page.scss'],
})
export class SignupPage implements OnInit {

  // form data object
  formData = {
    fullname : '',
    username : '',
    email : '',
    password : '',
    passwordConfirm : '',
    userAggrement : false,
  }

  constructor(
    private toastService: ToastService,
    private router: Router,
    private auth: AuthenticationService) { }

  ngOnInit() {
  }


  // check inputs
  checkFields(): boolean
  {
    // set clear variable
    let fullname = this.formData.fullname.trim();
    let username = this.formData.username.trim();
    let password = this.formData.password.trim();
    let passwordConfirm = this.formData.passwordConfirm.trim();
    let email = this.formData.email.trim();
    let userAggrement = this.formData.userAggrement;

    // check blanks
    if(
      fullname === "" ||
      username === "" ||
      password === "" ||
      passwordConfirm === "" ||
      email === ""
    )
    {
      this.toastService.warning("Boş Alan Bırakmayınız");
      return false;
    }

    // check username length
    if(username.length < 3)
    {
      this.toastService.warning("Kullanıcı adınız en az 3 haneli olmalıdır!");
      return false;
    }

    if(password.length < 6)
    {
      this.toastService.warning("Şifreniz en az 6 haneli olmalıdır!");
      return false;
    }

    // check match password
    if(
      password !== passwordConfirm
    )
    {
      this.toastService.error("Şifreler uyuşmuyor.");
      return false;
    }

    // check approve contract
    if(!userAggrement)
    {
      this.toastService.error("Lütfen Kullanıcı Sözleşmesini onaylayınız.");
      return false;
    }

    return true;
  }

  // create user
  signup()
  {
    if(this.checkFields())
    {
      this.auth.signup(JSON.stringify(this.formData)).subscribe(
        (res : any) => {
          console.log(res)
          if(res.message == 'INVALID_EMAIL_FORMAT')
          {
            this.toastService.error("Geçersiz E-Posta Formatı");
          }
          
          if(res.message == 'USER_AVAILABLE_IN_DB')
          {
            this.toastService.error("Girdiğiniz kullanıcı sistemimizde mevcuttur");
          }
          
          if(res.message == 'DO_NOT_LEAVE_EMPTY_SPACE')
          {
            this.toastService.warning("Boş alan bırakmayınız");
          }

          if(res.message == 'USER_REGISTRATION_SUCCESS')
          {
            this.toastService.success("Üye kayıt işlemi başarılı!");
            this.clearFormData();
            this.router.navigate(['login'])
          }
          
          if(res.message == 'USER_REGISTRATION_FAILED')
          {
            this.toastService.error("İşlem başarısız!");
          }
        }
      )
    }
  }

  clearFormData()
  {
    this.formData.fullname = ''
    this.formData.username = ''
    this.formData.email = ''
    this.formData.password = ''
    this.formData.passwordConfirm = ''
    this.formData.userAggrement = false
  }
}
