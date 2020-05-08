import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ConstantService {

  // API URL
  API_URL = 'http://localhost/bitirme/api/'

  // TOKEN
  AUTH = 'userData'

  constructor() { }
}
