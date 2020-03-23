import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-questions',
  templateUrl: './questions.page.html',
  styleUrls: ['./questions.page.scss'],
})


export class QuestionsPage implements OnInit {

  // auto complete fields
  autocompleteFields = [
    {
      value: 1, 
      display: 'Javascript'
    },
    {
      value: 2, 
      display: 'Java'
    },
    {
      value: 3, 
      display: 'React'
    },
  ]

  // filter items
  filterItems = [
    {
      value: 1, 
      display: 'Yeni'
    },
    {
      value: 2, 
      display: 'Son Aktivite'
    },
    {
      value: 3, 
      display: 'En çok puanlanan'
    },
    {
      value: 4, 
      display: 'En çok görüntülenen'
    },
  ]

  constructor() { }

  ngOnInit() {
  }

  // toggle for filter area
  isShowFilter: boolean = false;

  showFilter()
  {
    this.isShowFilter = !this.isShowFilter;
  }

  applyFilter()
  {
    
  }

  /*[ngModel]="checkedIdx == i" (ngModelChange)="$event ? checkedIdx = i : checkedIdx = -1"*/
}