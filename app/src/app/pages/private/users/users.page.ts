import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { UsersService } from '../../../services/users/users.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
import { User } from '../../../models/user.model';
import { ConstantService } from '../../../services/constant/constant.service'
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';
@Component({
  selector: 'app-users',
  templateUrl: './users.page.html',
  styleUrls: ['./users.page.scss'],
})
export class UsersPage {

  defaultSegment = 'popular';
  users : User[] = [];
  textFilter = '';

  API_URL = '';

  constructor(
    private http: HttpClient,
    private uService : UsersService,
    private auth : AuthenticationService,
    private loadingController : LoadingController,
    private cService : ConstantService,
    public alertController: AlertController,
    private router : Router
  ) { 
    this.auth.userData$.subscribe(res => {
      this.API_URL = this.cService.API_URL;
    })
  }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadUsers()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.users = [];
    this.defaultSegment = 'popular'
  }

  // segment change
  segmentChanged(ev: any) {
    this.ionViewWillLeave()
    this.defaultSegment = ev.detail.value
    this.loadUsers()
  }

  // search item
  filterText( event )
  {
    const text = event.target.value;
    this.textFilter = text;
  }

  // load data
  async loadUsers()
  {
      var postData = {
        sort : this.defaultSegment
      }

      const loading = await this.loadingController.create({
        message: 'Yükleniyor...',
        duration: 3000
      });

      await loading.present();

      this.auth.userData$.subscribe(res => {
        this.uService.getAllUsers(res.data, JSON.stringify(postData)).subscribe(response => {
          this.users = response
          loading.dismiss()
          console.log(this.users)
        })
      })
  }

  async presentAlertConfirm(UserID, Fullname) {
    
    const alert = await this.alertController.create({
      header: 'Seçiniz',
      message: '',
      buttons: [
        {
          text: 'Profili Gör',
          cssClass: 'success',
          handler: () => {
            console.log('profili gör', UserID);
            this.router.navigate(['/board/profile/', UserID])
          }
        }, {
          text: 'Mesaj Gönder',
          handler: () => {
            this.router.navigate(['/board/message/', UserID, Fullname])
          }
        }
      ]
    });

    await alert.present();
  }
}
