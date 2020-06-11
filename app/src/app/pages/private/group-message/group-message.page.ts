import { Component, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ConversationService } from 'src/app/services/conversation/conversation.service';
import { IonContent } from '@ionic/angular';

import { Socket } from 'ngx-socket-io';

@Component({
  selector: 'app-group-message',
  templateUrl: './group-message.page.html',
  styleUrls: ['./group-message.page.scss'],
})
export class GroupMessagePage {

  GroupInfo = {
    GroupID : '',
    GroupName : ''
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
    private conversationService : ConversationService,
    private socket : Socket
  ) { }

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")

    this.socket.connect();
    this.GroupInfo.GroupID = this.activatedRoute.snapshot.paramMap.get('id')
    this.GroupInfo.GroupName = this.activatedRoute.snapshot.paramMap.get('name')
    this.currentUserID = this.activatedRoute.snapshot.paramMap.get('userid')
    // get messages
    this.getMessages()

    // socket process
    let UserID = this.currentUserID
    let GroupID = this.GroupInfo.GroupID
    this.socket.emit('joinGroup', { UserID, GroupID })
    
    this.socket.fromEvent('message').subscribe(msg => {
      console.log(msg)
      this.conversation.Messages.push(msg)
      setTimeout(() => {
        this.content.scrollToBottom(200);
      })
    })
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
    this.clear()
    this.socket.disconnect();
  }

  // clear
  clear()
  {
    this.GroupInfo = {
      GroupID : '',
      GroupName : ''
    }

    this.conversation = {
      Messages : []
    }
    
    this.newMessage = '';
  }

  // Get Conversation Messages
  getMessages()
  {
    this.auth.userData$.subscribe(res => {
      this.conversationService.getGroupMessages(res.data, this.GroupInfo).subscribe(response => {

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

  // send message
  sendMessage()
  {
    this.auth.userData$.subscribe(res => {
      this.conversationService.sendGroupMessage(res.data, {Message: this.newMessage, GroupID: this.GroupInfo.GroupID}).subscribe(response => {
        console.log(response)
        if(response.message == "AUTHORIZATION_FAILED")
        {
          this.auth.logout();
        }

        if(response.message == "SEND_MESSAGE_SUCCESS")
        {
          this.socket.emit('send-message', response.data)
          // this.conversation.Messages.push(response.data)
          this.newMessage = ''
          setTimeout(() => {
            this.content.scrollToBottom(200);
          })
        }
      })
    })
  }
}
