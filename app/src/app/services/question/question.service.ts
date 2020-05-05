import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class QuestionService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Add Question
  addQuestion(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/add', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }

  // Get Last Questions
  getLastQuestions(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/get_last', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  } 

  // Get All Questions
  getAllQuestions(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'question/get_all', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  } 
}
