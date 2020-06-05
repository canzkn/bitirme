import { Component, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ConversationService } from 'src/app/services/conversation/conversation.service';
import { IonContent } from '@ionic/angular';
@Component({
  selector: 'app-message',
  templateUrl: './message.page.html',
  styleUrls: ['./message.page.scss'],
})
export class MessagePage {

  targetUser = {
    UserID : '',
    Fullname : ''
  }

  conversation = {
    Messages : []
  }
  
  currentUserID: any;
  newMessage = '';

  @ViewChild(IonContent) content: IonContent;

  constructor(
    private activatedRoute : ActivatedRoute,
    private auth : AuthenticationService,
    private conversationService : ConversationService
  ) { }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.auth.userData$.subscribe(logged => {
      this.currentUserID = logged.data.UserID;
    })
    this.targetUser.UserID = this.activatedRoute.snapshot.paramMap.get('id')
    this.targetUser.Fullname = this.activatedRoute.snapshot.paramMap.get('name')
    this.getMessages()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.clear()
  }

  // Get Conversation Messages
  getMessages()
  {
    this.auth.userData$.subscribe(res => {
      this.conversationService.getMessages(res.data, this.targetUser).subscribe(response => {

        if(response.message != "404_NOT_FOUND")
        {
          this.conversation.Messages = response
          console.log(this.conversation.Messages)
        }
        setTimeout(() => {
          this.content.scrollToBottom(200);
        })
      })
    })
  }

  // clear
  clear()
  {
    this.targetUser = {
      UserID : '',
      Fullname : ''
    }

    this.conversation = {
      Messages : []
    }
    
    this.newMessage = '';
  }

  // send message
  sendMessage()
  {
    this.auth.userData$.subscribe(res => {
      this.conversationService.sendMessage(res.data, {Message: this.newMessage, ReceiverID: this.targetUser.UserID}).subscribe(response => {
        console.log(response)
        if(response.message == "AUTHORIZATION_FAILED")
        {
          this.auth.logout();
        }

        if(response.message == "SEND_MESSAGE_SUCCESS")
        {
          this.conversation.Messages.push(response.data)
          this.newMessage = ''

          setTimeout(() => {
            this.content.scrollToBottom(200);
          })
        }
      })
    })
  }
}
