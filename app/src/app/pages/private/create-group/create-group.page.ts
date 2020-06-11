import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { UsersService } from '../../../services/users/users.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { Subscription } from 'rxjs';
import { LoadingController } from '@ionic/angular';
import { User } from '../../../models/user.model';
import { ConstantService } from '../../../services/constant/constant.service'
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';
import { ToastService } from 'src/app/services/toast/toast.service';
import { ConversationService } from 'src/app/services/conversation/conversation.service';

@Component({
  selector: 'app-create-group',
  templateUrl: './create-group.page.html',
  styleUrls: ['./create-group.page.scss'],
})
export class CreateGroupPage {

  users : User[] = [];
  textFilter = '';
  defaultSegment = 'popular';
  selected : number[] = [];

  API_URL = '';

  buttonDisable : boolean = true;

  postData = {
    GroupName : '',
    Users : []
  }

  constructor(
    private http: HttpClient,
    private uService : UsersService,
    private auth : AuthenticationService,
    private loadingController : LoadingController,
    private cService : ConstantService,
    public alertController: AlertController,
    private router : Router,
    private tService : ToastService,
    private conversationService : ConversationService
  ) { 
    this.auth.userData$.subscribe(res => {
      this.API_URL = this.cService.API_URL;
    })
  }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadUsers()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.users = [];
    this.selected = [];
  }

  // search item
  filterText( event )
  {
    const text = event.target.value;
    this.textFilter = text;
  }

  // load data
  async loadUsers()
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
        this.selected.push(res.data.UserID)
        this.uService.getAllUsers(res.data, JSON.stringify(postData)).subscribe(response => {
          this.users = response
          
          loading.dismiss()
          // console.log(this.users)

          for(let i = 0; i < this.users.length; i++)
          {
            if(res.data.UserID == this.users[i].UserID)
            {
              console.log("+")
              this.users.splice(i, 1)
              break;
            }
          }
        })
      })
  }

  // select an element
  select(TagID)
  {
    this.selected.push(TagID)

    if(this.selected.length > 1)
    {
      this.buttonDisable = false;
    }
  }

  // unselect an element
  unselect(TagID)
  {
    const index: number = this.selected.indexOf(TagID);
    if (index !== -1) {
        this.selected.splice(index, 1);
    }

    if(this.selected.length == 1)
    {
      this.buttonDisable = true;
    }
  }

  // element is selected?
  isSelect(TagID)
  {
    return this.selected.find(x => x == TagID)
  }

  // submit selected items
  submit()
  {
    if(this.selected.length > 1)
    {
      if(!this.postData.GroupName)
      {
        this.tService.warning("Lütfen grup ismi giriniz.")
      }
      else
      {
        this.postData.Users = this.selected

        this.auth.userData$.subscribe(res => {
          this.conversationService.createGroup(res.data, JSON.stringify(this.postData)).subscribe(response => {
            if(response.message == "CREATE_GROUP_SUCCESS")
            {
              this.router.navigate(['/board/group-message', response.ConversationID, this.postData.GroupName, res.data.UserID])
            }

            if(response.message == "CREATE_GROUP_FAILED")
            {
              this.tService.warning("Grup oluşturma başarısız")
            }
  
            if(response.message == "AUTHORIZATION_FAILED")
            {
              this.auth.logout();
            }
          })
        })
      }
    }
  }

}
