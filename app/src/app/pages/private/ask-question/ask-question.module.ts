import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AskQuestionPageRoutingModule } from './ask-question-routing.module';

import { AskQuestionPage } from './ask-question.page';

import { ComponentsModule } from '../../../components/components.module';

import { TagInputModule } from 'ngx-chips';
        
import { QuillModule } from 'ngx-quill'

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AskQuestionPageRoutingModule,
    ComponentsModule,
    TagInputModule,
    QuillModule.forRoot({
      modules: {
        syntax: true
      }
    })
  ],
  declarations: [AskQuestionPage]
})
export class AskQuestionPageModule {}
