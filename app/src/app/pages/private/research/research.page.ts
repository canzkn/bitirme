import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TagsService } from '../../../services/tags/tags.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { LoadingController } from '@ionic/angular';
import { Tags } from '../../../models/tags.model';
import { ConstantService } from '../../../services/constant/constant.service'
import { ResearchService } from '../../../services/research/research.service'

@Component({
  selector: 'app-research',
  templateUrl: './research.page.html',
  styleUrls: ['./research.page.scss'],
})
export class ResearchPage {

  tags : Tags[] = [];
  postData = {
    query : '',
    language: '',
    page : 1
  }

  found = {Found:0, Repositories: []};

  constructor(
    private http: HttpClient,
    private tService : TagsService,
    private auth : AuthenticationService,
    private loadingController : LoadingController,
    private constantService : ConstantService,
    private rService : ResearchService,
  ) { }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadTags()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.tags = [];
    this.found = {Found:0, Repositories: []};
    this.postData = {
      query : '',
      language: '',
      page : 1
    }
  }

  // load tags
  async loadTags()
  {
    var postData = {
      sort : ''
    }

    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    await loading.present();

    this.auth.userData$.subscribe(res => {
      this.tService.getAllTags(res.data, JSON.stringify(postData)).subscribe(response => {
        this.tags = response
        console.log(this.tags)
        loading.dismiss()
      })
    })
  }

  // search
  async search(pageId)
  {
    if(pageId == 1)
    {
      this.postData.page = 1
      this.found.Repositories = [];
    }

    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    await loading.present();
    console.log(JSON.stringify(this.postData))
    this.auth.userData$.subscribe(res => {
      this.rService.search(res.data, JSON.stringify(this.postData)).subscribe(response => {
        this.found.Found = response.Found;
        this.found.Repositories = this.found.Repositories.concat(response.Repositories);
        loading.dismiss()
      })
    })
  }

  // load more data
  loadMore(event) {
    setTimeout(() => {
      console.log('loadMore');
      event.target.complete();

      // App logic to determine if all data is loaded
      this.postData.page++
      this.search(this.postData.page)
    }, 500);
  }
}
