import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { QuestionService } from '../../../services/question/question.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-home',
  templateUrl: './home.page.html',
  styleUrls: ['./home.page.scss'],
})
export class HomePage {

  private _userDataListener: Subscription = new Subscription();
  pageId = 1;
  questions = [];
  defaultSegment = 'hot';
  currentEvent;

  constructor(
    private http: HttpClient,
    private qService : QuestionService,
    private auth : AuthenticationService,
    private loadingController : LoadingController
  ) { }

  

  ionViewWillEnter()
  {
    this.loadQuestions(this.defaultSegment, this.pageId)
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    if(this.currentEvent)
    {
      // console.log(this.currentEvent)
      this.currentEvent.target.disabled = false
    }
    this._userDataListener.unsubscribe()
    this.questions = [];
    this.pageId = 1;
    this.defaultSegment = 'hot';
  }

  // load questions
  async loadQuestions(queryFilter, queryPageID, event?)
  {
      var postData = {
        filter : queryFilter,
        page_id : queryPageID,
      }

      const loading = await this.loadingController.create({
        message: 'Yükleniyor...',
        duration: 3000
      });

      if (queryPageID == 1)
      {
        await loading.present();
      }

      this._userDataListener = this.auth.userData$.subscribe(res => {
        // console.log(res)
        this.qService.getLastQuestions(res.data, JSON.stringify(postData)).subscribe(response => {
          if(response.message == "AUTHORIZATION_FAILED")
          {
            this.auth.logout();
          }
          else
          {
            this.questions = this.questions.concat(response.data)

            if(event)
            {
              if(response.total_page == queryPageID)
              {
                event.target.disabled = true;
              }
            }

            // console.log(response)
          }

          if(queryPageID == 1)
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

  // segment change
  segmentChanged(ev: any) {
    this.ionViewWillLeave()
    this.defaultSegment = ev.detail.value
    this.loadQuestions(this.defaultSegment, this.pageId)
  }

  // load more data
  loadMore(event)
  {
    this.pageId++
    this.currentEvent = event;
    this.loadQuestions(this.defaultSegment, this.pageId, event)
  }
}
