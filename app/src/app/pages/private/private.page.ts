import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthenticationService } from '../../services/authenticaton/authentication.service';
import { ProfileService } from '../../services/profile/profile.service';
import { ConstantService } from 'src/app/services/constant/constant.service';

@Component({
  selector: 'app-private',
  templateUrl: './private.page.html',
  styleUrls: ['./private.page.scss'],
})
export class PrivatePage {

  API_URL = ''

  activeProfile = {
    Username: '',
    Email: '',
    Fullname: '',
    Address: '',
    AvatarImage: '',
    CoverImage: '',
    Reputation: ''
  }

  
  UserID;
  // menu items
  public appPages = [
    {
      title: 'Akış',
      url: '/board/home',
      icon: 'globe-sharp',
    },
    {
      title: 'Tüm Sorular',
      url: '/board/questions',
      icon: 'help-circle-sharp'
    },
    {
      title: 'Etiketler',
      url: '/board/tags',
      icon: 'pricetags-sharp'
    },
    {
      title: 'Kullanıcılar',
      url: '/board/users',
      icon: 'people-sharp'
    },
    {
      title: 'Kaynak Kod Arama',
      url: '/board/research',
      icon: 'code-slash-sharp'
    }
  ];

  // profile items

  public profilePages = [
    {
      title: 'Profil Sayfam',
      url: '/board/profile',
      icon: 'person-sharp',
    },
    {
      title: 'Bilgilerimi Düzenle',
      url: '/board/edit-profile',
      icon: 'create-sharp'
    },
    {
      title: 'Mesaj Kutusu',
      url: '/board/message-box',
      icon: 'chatbubbles-sharp'
    }
  ];

  constructor(
    private router: Router,
    private auth : AuthenticationService,
    private pService : ProfileService,
    private cService : ConstantService
    ) { 
      this.API_URL = this.cService.API_URL;
  }

  ionViewWillEnter()
  {
    this.pService.activeProfile.next(null);
    this.pService.activeProfile.subscribe(data => {
      if(data === null)  
      {
        this.pService.loadActiveProfile()
      }
      else
      {
        this.activeProfile = data
      }
    })

    this.auth.userData$.subscribe(res => {
      this.UserID = res.data.UserID
    })
  }

  ionViewWillLeave()
  {

  }

  isInterestPage(): boolean
  {
    return this.router.url === '/board/interest'
  }

  logout()
  {
    this.auth.logout()
  }
}
