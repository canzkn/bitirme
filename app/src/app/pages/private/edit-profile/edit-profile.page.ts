import { Component, OnInit } from '@angular/core';
import { CameraOptions, Camera } from '@ionic-native/camera/ngx';
import { Platform } from '@ionic/angular';
import { ConstantService } from '../../../services/constant/constant.service';
import { ProfileService } from '../../../services/profile/profile.service';
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ToastService } from '../../../services/toast/toast.service';
@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.page.html',
  styleUrls: ['./edit-profile.page.scss'],
})
export class EditProfilePage {

  postData = {
    Username: '',
    Email: '',
    Password: '',
    Fullname: '',
    Address: '',
    AvatarImage: '',
    CoverImage: '',
  }

  API_URL = '';
  
  constructor(
    private camera : Camera,
    private platform : Platform,
    private cService : ConstantService,
    private pService : ProfileService,
    private auth : AuthenticationService,
    private toast : ToastService,
    ) { 
      this.API_URL = this.cService.API_URL;
    }
  

  ionViewWillEnter()
  {
    console.log("ionViewWillEnter")
    this.loadProfile()
  }

  ionViewWillLeave()
  {
    console.log("ionViewWillLeave")
  }

  // load user
  loadProfile()
  {
    this.auth.userData$.subscribe(res => {
      this.pService.getMyProfile(res.data).subscribe(response => {
        this.postData = response
        this.postData.AvatarImage = this.API_URL + response.AvatarImage
        this.postData.CoverImage = this.API_URL + response.CoverImage
      })
    })
  }

  // update
  update()
  {
    console.log(JSON.stringify(this.postData))
    this.auth.userData$.subscribe(res => {
      this.pService.updateMyProfile(res.data, JSON.stringify(this.postData)).subscribe(response => {
        if(response.message == "USER_PROFILE_UPDATE_SUCCESS")
        {
          this.toast.success("Profiliniz başarı ile güncellendi!");
          this.loadProfile()
        }
      })
    })
  }

  // upload avatar
  uploadAvatar()
  {
    if(this.platform.is('desktop'))
    {
      // web file upload
      var AvatarFile = document.getElementById('AvatarFile');
      AvatarFile.click();
    }
    else
    {
      //camera file upload
      this.camera.getPicture({

        sourceType: this.camera.PictureSourceType.SAVEDPHOTOALBUM,
    
        destinationType: this.camera.DestinationType.DATA_URL
    
       }).then((imageData) => {
    
         this.postData.AvatarImage = 'data:image/jpeg;base64,'+imageData;
        }, (err) => {
    
         console.log(err);
    
       });
    }
  }

  // upload cover
  uploadCover()
  {
    if(this.platform.is('desktop'))
    {
      // web file upload
      var CoverFile = document.getElementById('CoverFile');
      CoverFile.click();
    }
    else
    {
      //camera file upload
      this.camera.getPicture({

        sourceType: this.camera.PictureSourceType.SAVEDPHOTOALBUM,
    
        destinationType: this.camera.DestinationType.DATA_URL
    
       }).then((imageData) => {
    
         this.postData.CoverImage = 'data:image/jpeg;base64,'+imageData;
        }, (err) => {
    
         console.log(err);
    
       });
    }
  }

  // Avatar Change Event
  AvatarChangeEvent(fileInput: any) {
    const reader = new FileReader();
    reader.onload = (e: any) => {
        const image = new Image();
        image.src = e.target.result;
        image.onload = rs => {
            const imgBase64Path = e.target.result;
            this.postData.AvatarImage = imgBase64Path
        };
    };
    reader.readAsDataURL(fileInput[0]);
  }

  // Cover Change Event
  CoverChangeEvent(fileInput: any) {
    const reader = new FileReader();
    reader.onload = (e: any) => {
        const image = new Image();
        image.src = e.target.result;
        image.onload = rs => {
            const imgBase64Path = e.target.result;
            this.postData.CoverImage = imgBase64Path
        };
    };
    reader.readAsDataURL(fileInput[0]);
  }
}
