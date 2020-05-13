import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ProfileService } from '../../../services/profile/profile.service';
import { LoadingController } from '@ionic/angular';
import { ConstantService } from '../../../services/constant/constant.service';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage {

  API_URL = '';

  profileData = {
    UserID: '',
    Username: '',
    Email: '',
    Fullname: '',
    Information: '',
    Address: '',
    RegisterDate: '',
    LastSeen: '',
    ProfileViews: '',
    AvatarImage: '',
    CoverImage: '',
    isInterest: '',
    Reputation: '',
    AskedCount: '',
    AnswerCount: '',
    QuestionCount: '',
    Questions: [{
		QuestionID: '',
		Title: '',
		Reputation: '',
		Status: '',
		UpdateDate: '',
		UpdateDateString: '',
	}],
    TagCount: '',
    Tags: [{
        TagID: '',
        TagName: '',
    }]
  }

  postData = {
    UserID : '',
    filter : {
      type : 'all',
      sort : 'popular'
    }
  }

  interaction: number = 0;

  constructor(
    private activatedRoute: ActivatedRoute,
    private http : HttpClient,
    private auth : AuthenticationService,
    private pService : ProfileService,
    private router : Router,
    private loadingController : LoadingController,
    private cService : ConstantService) { 
      this.API_URL = this.cService.API_URL;
    }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.postData.UserID = this.activatedRoute.snapshot.paramMap.get('id');
    this.loadData()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.clear()
  }

  // filter segment change
  filterSegmentChanged(ev: any) {
    this.clearPostData()
    this.postData.filter.type = ev.detail.value
    this.loadData()
  }

  // sort segment change
  sortSegmentChanged(ev: any) {
    this.clearPostData()
    this.postData.filter.sort = ev.detail.value
    this.loadData()
  }

  // Get Question Datas
  async loadData()
  {
    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    this.auth.userData$.subscribe(res => {
      this.pService.getProfile(res.data, this.postData).subscribe(response => {
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
          
          this.profileData = response
          
          console.log(this.profileData)
          var askedcount = +this.profileData.AskedCount
          var answercount = +this.profileData.AnswerCount
          this.interaction = askedcount+answercount
        }

        loading.dismiss()
      })
    })
  }

  clearPostData()
  {
    this.profileData = {
      UserID: '',
      Username: '',
      Email: '',
      Fullname: '',
      Information: '',
      Address: '',
      RegisterDate: '',
      LastSeen: '',
      ProfileViews: '',
      AvatarImage: '',
      CoverImage: '',
      isInterest: '',
      Reputation: '',
      AskedCount: '',
      AnswerCount: '',
      QuestionCount: '',
      Questions: [{
      QuestionID: '',
      Title: '',
      Reputation: '',
      Status: '',
      UpdateDate: '',
      UpdateDateString: '',
    }],
      TagCount: '',
      Tags: [{
          TagID: '',
          TagName: '',
      }]
    }
  }

  // clear data
  clear()
  {
    this.profileData = {
      UserID: '',
      Username: '',
      Email: '',
      Fullname: '',
      Information: '',
      Address: '',
      RegisterDate: '',
      LastSeen: '',
      ProfileViews: '',
      AvatarImage: '',
      CoverImage: '',
      isInterest: '',
      Reputation: '',
      AskedCount: '',
      AnswerCount: '',
      QuestionCount: '',
      Questions: [{
      QuestionID: '',
      Title: '',
      Reputation: '',
      Status: '',
      UpdateDate: '',
      UpdateDateString: '',
    }],
      TagCount: '',
      Tags: [{
          TagID: '',
          TagName: '',
      }]
    }

    this.postData = {
      UserID : this.activatedRoute.snapshot.paramMap.get('id'),
      filter : {
        type : 'all',
        sort : 'popular'
      }
    }
  }
}
