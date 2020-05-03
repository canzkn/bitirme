import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ToastService } from '../../../services/toast/toast.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service'
import { ConstantService } from '../../../services/constant/constant.service';
import { StorageService } from '../../../services/storage/storage.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  constructor(
    private toastService: ToastService,
    private router: Router,
    private auth: AuthenticationService,
    private storageService: StorageService,
    private constantService: ConstantService) { }

  ngOnInit() {
  }

  // form data object
  formData = {
    username : '',
    password : ''
  }

  // check inputs
  checkFields(): boolean
  {
    // set clear variable
    let username = this.formData.username.trim();
    let password = this.formData.password.trim();
    // check blanks
    if(
      username === "" ||
      password === ""
    )
    {
      this.toastService.warning("Boş Alan Bırakmayınız");
      return false;
    }

    return true;
  }

  // login user
  login()
  {
    if(this.checkFields())
    {
      console.log(JSON.stringify(this.formData))
      this.auth.login(JSON.stringify(this.formData)).subscribe(
        (res : any) => {
          console.log(res)
          if(res.message == 'LOGIN_FAILED')
          {
            this.toastService.error("Giriş başarısız!");
          }
          
          if(res.message == 'DO_NOT_LEAVE_IN_BLANK')
          {
            this.toastService.warning("Boş alan bırakmayınız!");
          }

          if(res.message == 'ALREADY_LOGGED')
          {
            this.toastService.warning("Zaten oturum açmışsınız!");
          }

          if(res.message == 'TOKEN_CREATE_FAILED')
          {
            this.toastService.error("Token oluşturulamadı, tekrar deneyiniz.");
          }

          if(res.message == 'LOGIN_SUCCESS')
          {
            this.storageService.store(this.constantService.AUTH, res)
            if(res.data.isInterest == 1)
            {
              this.router.navigate(['board/home']);
            }
            else
            {
              this.router.navigate(['board/interest']);
            }
          }
        }
      )
    }
  }

  clearFormData()
  {
    this.formData.username = ''
    this.formData.password = ''
  }

}
