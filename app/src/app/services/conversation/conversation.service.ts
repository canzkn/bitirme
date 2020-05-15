import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ConversationService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // get conversation
  getConversations(auth) : Observable<any>
  {
    return this.http.get(this.constantService.API_URL + 'conversation', {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // get conversation messages
  getMessages(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'conversation/messages', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Send message
  sendMessage(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'conversation/send_message', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
