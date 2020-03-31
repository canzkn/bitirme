import { Component, OnInit } from '@angular/core';
 
@Component({
  selector: 'app-ask-question',
  templateUrl: './ask-question.page.html',
  styleUrls: ['./ask-question.page.scss'],
})
export class AskQuestionPage implements OnInit {

  htmlText =""

  // auto complete fields
  autocompleteFields = [
    {
      display: 'Javascript'
    },
    {
      display: 'Java'
    },
    {
      display: 'React'
    },
  ]

  constructor() { }

  ngOnInit() {

  }

  deneme() {
    console.log(this.htmlText);
  }

}
