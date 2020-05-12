import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { TagsService } from '../../../services/tags/tags.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
import { Tags } from '../../../models/tags.model';
import { ConstantService } from '../../../services/constant/constant.service'
@Component({
  selector: 'app-tags',
  templateUrl: './tags.page.html',
  styleUrls: ['./tags.page.scss'],
})
export class TagsPage {

  defaultSegment = 'popular';
  tags : Tags[] = [];
  textFilter = '';

  constructor(
    private http: HttpClient,
    private tService : TagsService,
    private auth : AuthenticationService,
    private loadingController : LoadingController,
    private constantService : ConstantService,
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
  }

  // segment change
  segmentChanged(ev: any) {
    this.ionViewWillLeave()
    this.defaultSegment = ev.detail.value
    this.loadTags()
  }

  // search item
  filterText( event )
  {
    const text = event.target.value;
    this.textFilter = text;
  }

  // load tags
  async loadTags()
  {
      var postData = {
        sort : this.defaultSegment
      }

      const loading = await this.loadingController.create({
        message: 'Yükleniyor...',
        duration: 3000
      });

      await loading.present();

      this.auth.userData$.subscribe(res => {
        // console.log(res)
        this.tService.getAllTags(res.data, JSON.stringify(postData)).subscribe(response => {
          this.tags = response
          loading.dismiss()
        })
      })
  }
}
