import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProfileService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Add Interest
  addInterest(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'profile/interest/add', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
