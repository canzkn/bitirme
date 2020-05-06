import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { QuestionService } from '../../../services/question/question.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
import { Tag } from '../../../models/tag.model';
import { ConstantService } from '../../../services/constant/constant.service'

@Component({
  selector: 'app-questions',
  templateUrl: './questions.page.html',
  styleUrls: ['./questions.page.scss'],
})


export class QuestionsPage {

  private _userDataListener: Subscription = new Subscription();
  questions = [];
  currentEvent;
  totalData : number = 0;

  filterButton = true;

  postData = {
    type : 'new',
    filter : {
      unanswered : false,
      acceptedanswer : false,
      sortCheck : -1,
      followCheck : -1,
      tags: '',
    },
    pageId: 1
  }

  // auto complete fields
  autocompleteFields = []

  // filter items
  filterItems = [
    {
      display: 'Yeni'
    },
    {
      display: 'Son Aktivite'
    },
    {
      display: 'En çok puanlanan'
    },
    {
      display: 'En çok görüntülenen'
    },
  ]

  // follow items
  followItems = [
    {
      display: 'İzlediğim Etiketler'
    },
    {
      display: 'Aşağıdaki Etiketler'
    }
  ]

  constructor(
    private http: HttpClient,
    private qService : QuestionService,
    private auth : AuthenticationService,
    private loadingController : LoadingController,
    private constantService : ConstantService,
  ) { 
    this.http.get<Tag[]>(this.constantService.API_URL + 'tags').subscribe(res => {    
      for(let i=0; i < res.length; i++)
      {
        this.autocompleteFields.push({display: res[i].TagName, value: res[i].TagID})
      }
    })
  }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadQuestions(this.postData)
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.clear() 
    this._userDataListener.unsubscribe()
    this.questions = [];
  }

  // toggle for filter area
  isShowFilter: boolean = false;

  showFilter()
  {
    this.isShowFilter = !this.isShowFilter;
  }

  applyFilter()
  {
    // console.log(this.sortCheck, this.followCheck)
    this.postData.type = 'special'
    console.log(JSON.stringify(this.postData))
  }

  // segment change
  segmentChanged(ev: any) {
    this.ionViewWillLeave()
    if(this.currentEvent)
    {
      // console.log(this.currentEvent)
      this.currentEvent.target.disabled = false
    }
    this.postData.type = ev.detail.value
    this.loadQuestions(this.postData)
  }

  // clear data
  clear()
  {
    this.postData.type = 'new'
    this.postData.filter.unanswered = false
    this.postData.filter.acceptedanswer = false
    this.postData.filter.sortCheck = -1
    this.postData.filter.followCheck = -1
    this.postData.filter.tags = ''
    this.postData.pageId = 1
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
    console.log(postData)
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