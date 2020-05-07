import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ConstantService } from '../../../services/constant/constant.service'
import { Tag } from '../../../models/tag.model';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { QuestionService } from '../../../services/question/question.service';
import { ToastService } from '../../../services/toast/toast.service';
import { Router } from '@angular/router';
@Component({
  selector: 'app-ask-question',
  templateUrl: './ask-question.page.html',
  styleUrls: ['./ask-question.page.scss'],
})
export class AskQuestionPage implements OnInit {

  postData = {
    title: "",
    content: "",
    tags: null,
  }

  // auto complete fields
  autocompleteFields = []

  constructor(
    private http : HttpClient,
    private constantService : ConstantService,
    private toast : ToastService,
    private auth : AuthenticationService,
    private qService : QuestionService,
    private router : Router) { }

  ngOnInit() {
    this.http.get<Tag[]>(this.constantService.API_URL + 'tags').subscribe(res => {    
      for(let i=0; i < res.length; i++)
      {
        this.autocompleteFields.push({display: res[i].TagName, value: res[i].TagID})
      }
    })
  }

  // check inputs
  checkFields(): boolean
  {
    // set clear variable
    let title = this.postData.title.trim();
    let content = this.postData.content.trim();
    let tags = this.postData.tags;
    // check blanks
    if(
      title == "" ||
      content == "" ||
      tags == null
    )
    {
      this.toast.warning("Boş Alan Bırakmayınız");
      return false;
    }

    return true;
  }

  // add question
  addQuestion() {
    if(this.checkFields())
    {
      this.auth.userData$.subscribe(res => {
        this.qService.addQuestion(res.data, JSON.stringify(this.postData)).subscribe(response => {
          if(response.message == "ADD_QUESTION_SUCCESS")
          {
            this.toast.success("Sorunuz başarı ile eklenmiştir!");
            this.clear();
            this.router.navigate(['/board/show-question/', response.QuestionID])
          }

          if(response.message == "ADD_QUESTION_FAILED")
          {
            this.toast.error("İşlem başarısız!");
          }

          if(response.message == "AUTHORIZATION_FAILED")
          {
            this.auth.logout();
          }
        })
      })
    }
  }

  // clear inputs
  clear()
  {
    this.postData.title = "";
    this.postData.content = "";
    this.postData.tags = null;
  }
}
