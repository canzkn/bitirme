import { Injectable } from '@angular/core';
import { AuthenticationService } from '../authenticaton/authentication.service';

@Injectable({
  providedIn: 'root'
})
export class ResolverService {

  constructor(private authenticationService : AuthenticationService) { }

  resolve() 
  {
    return this.authenticationService.isLogged();
  }
}
