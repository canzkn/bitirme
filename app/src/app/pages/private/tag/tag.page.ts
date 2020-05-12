import { Component } from '@angular/core';
import { Router, ActivatedRoute, NavigationExtras } from '@angular/router';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { QuestionService } from '../../../services/question/question.service';
import { ConstantService } from '../../../services/constant/constant.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
@Component({
  selector: 'app-tag',
  templateUrl: './tag.page.html',
  styleUrls: ['./tag.page.scss'],
})
export class TagPage {

  private _userDataListener: Subscription = new Subscription();
  questions = [];
  currentEvent;
  totalData : number = 0;

  postData = {
    type : 'special',
    filter : {
      unanswered : false,
      acceptedanswer : false,
      sortCheck : 1,
      followCheck : 1,
      tags: [{
        value : '',
        display : '',
      }],
    },
    pageId: 1
  }

  constructor(
    private activatedRoute: ActivatedRoute,
    private auth: AuthenticationService,
    private qService : QuestionService,
    private loadingController : LoadingController,
    private cService : ConstantService,
    private router : Router
  ) { }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.postData.filter.tags[0].value = this.activatedRoute.snapshot.paramMap.get('id');
    this.postData.filter.tags[0].display = this.activatedRoute.snapshot.paramMap.get('name');
    this.loadQuestions(this.postData)
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.clear() 
    this._userDataListener.unsubscribe()
    this.questions = [];
    if(this.currentEvent)
    {
      this.currentEvent.target.disabled = false
    }
  }

  // clear data
  clear()
  {
    this.postData = {
      type : 'special',
      filter : {
        unanswered : false,
        acceptedanswer : false,
        sortCheck : 1,
        followCheck : 1,
        tags: [{
          value : '',
          display : '',
        }],
      },
      pageId: 1
    }
  }

  // load questions
  async loadQuestions(postData, event?)
  {
    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    if (postData.pageId == 1)
    {
      await loading.present();
    }
    this._userDataListener = this.auth.userData$.subscribe(res => {
      // console.log(res)
      this.qService.getAllQuestions(res.data, JSON.stringify(postData)).subscribe(response => {
        if(response.message == "AUTHORIZATION_FAILED")
        {
          this.auth.logout();
        }
        else
        {
          console.log(response)
          this.questions = this.questions.concat(response.data)
          this.totalData = response.total_data
          if(event)
          {
            if(response.total_page == this.postData.pageId)
            {
              event.target.disabled = true;
            }
          }
        }

        if(postData.pageId == 1)
        {
          loading.dismiss()
        }
      })
    })

    if( event )
    {
      event.target.complete();
    }
  }

  // load more data
  loadMore(event)
  {
    this.postData.pageId++
    this.currentEvent = event;
    this.loadQuestions(this.postData, event)
  }
}
