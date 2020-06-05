import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';
@Injectable({
  providedIn: 'root'
})
export class ResearchService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Get All Tags
  search(auth, post_data) : Observable<any>
  {
    return this.http.post(this.constantService.API_URL + 'research', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
