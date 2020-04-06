import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-private',
  templateUrl: './private.page.html',
  styleUrls: ['./private.page.scss'],
})
export class PrivatePage implements OnInit {

  // menu items

  public appPages = [
    {
      title: 'Son Cevaplanan Sorular',
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
    },
    {
      title: 'Çıkış Yap',
      url: '/login',
      icon: 'log-out-sharp'
    }
  ];

  constructor() { }

  ngOnInit() {
  }

}
