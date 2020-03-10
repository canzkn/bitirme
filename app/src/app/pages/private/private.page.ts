import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-private',
  templateUrl: './private.page.html',
  styleUrls: ['./private.page.scss'],
})
export class PrivatePage implements OnInit {

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

  constructor() { }

  ngOnInit() {
  }

}
