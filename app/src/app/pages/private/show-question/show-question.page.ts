import { Component } from '@angular/core';
import { Router, ActivatedRoute, NavigationExtras } from '@angular/router';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { QuestionService } from '../../../services/question/question.service';
import { ConstantService } from '../../../services/constant/constant.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
import { ToastService } from '../../../services/toast/toast.service';

@Component({
  selector: 'app-show-question',
  templateUrl: './show-question.page.html',
  styleUrls: ['./show-question.page.scss'],
})
export class ShowQuestionPage {

  private _userDataListener: Subscription = new Subscription();

  API_URL = '';

  postData = {
    QuestionID : null
  }

  answerPostData = {
    QuestionID : '',
    Content : '',
    UserID : ''
  }

  Question = {
    QuestionID : '',
    Title : '',
    Content : '',
    View : '',
    CreateDate : '',
    CreateDateString : '',
    Reputation : '',
    UpdateDate : '',
    User : {
      UserID : '',
      Fullname : '',
      AvatarImage : '',
      Reputation : ''
    },
    Tags : [{
      TagID : '',
      TagName : '',
    }]
  }

  htmlText = ""

  constructor(
    private activatedRoute: ActivatedRoute,
    private auth: AuthenticationService,
    private qService : QuestionService,
    private loadingController : LoadingController,
    private cService : ConstantService,
    private toast : ToastService,
    private router : Router
    ) { 
      this.API_URL = this.cService.API_URL;
    }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.postData.QuestionID = this.activatedRoute.snapshot.paramMap.get('id');
    this.answerPostData.QuestionID = this.activatedRoute.snapshot.paramMap.get('id');
    this.getQuestion()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this._userDataListener.unsubscribe()
    this.clear()
  }

  // Get Question Datas
  async getQuestion()
  {
    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    

    this._userDataListener = this.auth.userData$.subscribe(res => {
      this.answerPostData.UserID = res.data.UserID
      this.qService.getQuestion(res.data, this.postData).subscribe(response => {
        if(response.message == "AUTHORIZATION_FAILED")
        {
          this.auth.logout();
        }
        
        if (response.message == "404_NOT_FOUND")
        {
          console.log("404_NOT_FOUND"),
          this.router.navigate(['/board/home'])
        }
        else
        {
          this.Question = response
          loading.dismiss()
          console.log(this.Question)
        }
      })
    })
  }

  // clear data
  clear()
  {
    this.Question = {
      QuestionID : '',
      Title : '',
      Content : '',
      View : '',
      CreateDate : '',
      CreateDateString : '',
      Reputation : '',
      UpdateDate : '',
      User : {
        UserID : '',
        Fullname : '',
        AvatarImage : '',
        Reputation : ''
      },
      Tags : [{
        TagID : '',
        TagName : '',
      }]
    }

    this.answerPostData = {
      QuestionID : '',
      Content : '',
      UserID : ''
    }
  }

  // add answer
  addAnswer()
  {
    if(this.checkFields())
    {
      this.auth.userData$.subscribe(res => {
        this.qService.addAnswer(res.data, JSON.stringify(this.answerPostData)).subscribe(response => {
          if(response.message == "ADD_ANSWER_SUCCESS")
          {
            this.toast.success("Cevabınız başarı ile eklenmiştir!");
            this.answerPostData.Content = ''
            this.router.navigate(['/board/show-question/', this.postData.QuestionID])
          }

          if(response.message == "ADD_ANSWER_FAILED")
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

  // check inputs
  checkFields(): boolean
  {
    // set clear variable
    let content = this.answerPostData.Content.trim();
    // check blanks
    if(content == "")
    {
      this.toast.warning("Boş Alan Bırakmayınız");
      return false;
    }

    return true;
  }
}
