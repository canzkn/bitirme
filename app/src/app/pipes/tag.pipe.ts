import { Pipe, PipeTransform } from '@angular/core';
import { Tag } from '../models/tag.model'
@Pipe({
  name: 'tagFilter'
})
export class TagPipe implements PipeTransform {

  transform(tags: Tag[], text: string): Tag[] {
    if( text.length === 0) { return tags; }

    text = text.toLocaleLowerCase();

    return tags.filter( data => {
      return data.TagName.toLocaleLowerCase().includes(text);
    });
  }

}
