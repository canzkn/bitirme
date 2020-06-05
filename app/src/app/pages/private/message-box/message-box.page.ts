import { Component } from '@angular/core';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ConversationService } from 'src/app/services/conversation/conversation.service';
import { LoadingController } from '@ionic/angular';
import { ConstantService } from '../../../services/constant/constant.service';
@Component({
  selector: 'app-message-box',
  templateUrl: './message-box.page.html',
  styleUrls: ['./message-box.page.scss'],
})
export class MessageBoxPage {
  API_URL = '';

  conversation = [];

  constructor(
    private auth : AuthenticationService,
    private conversationService : ConversationService,
    private loadingController : LoadingController,
    private cService : ConstantService,
  ) {
    this.API_URL = this.cService.API_URL;
    
   }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadConversations()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.conversation = [];
  }

  // load conversations
  async loadConversations()
  {
    const loading = await this.loadingController.create({
      message: 'Yükleniyor...',
      duration: 3000
    });

    await loading.present();

    this.auth.userData$.subscribe(res => {
      this.conversationService.getConversations(res.data).subscribe(response => {
        if(response.message == "AUTHORIZATION_FAILED")
        {
          this.auth.logout();
        }

        if(response.message != "404_NOT_FOUND")
        {
          this.conversation = response
          console.log(this.conversation)
        }
        
        loading.dismiss()
      })
    })
  }
}
