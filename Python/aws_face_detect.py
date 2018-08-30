import boto3
import io
#import MySQLdb
from PIL import Image


BUCKET = "pga-test-images"
KEY = "test.jpg"
FEATURES_BLACKLIST = ("Landmarks", "Pose")

#db = MySQLdb.connect(host="localhost",    # your host, usually localhost
#                     user="john",         # your username
#                     passwd="megajonhy",  # your password
#                     db="jonhydb")        # name of the data base

#cur = db.cursor()   #have to create a object

def ShowBoundingBoxPositions(imageHeight, imageWidth, box, rotation):
    left = 0
    top = 0

    if rotation == 'ROTATE_0':
        left = imageWidth * box['Left']
        top = imageHeight * box['Top']

    if rotation == 'ROTATE_90':
        left = imageHeight * (1 - (box['Top'] + box['Height']))
        top = imageWidth * box['Left']

    if rotation == 'ROTATE_180':
        left = imageWidth - (imageWidth * (box['Left'] + box['Width']))
        top = imageHeight * (1 - (box['Top'] + box['Height']))

    if rotation == 'ROTATE_270':
        left = imageHeight * box['Top']
        top = imageWidth * (1- box['Left'] - box['Width'] )

    print('Left: ' + '{0:.0f}'.format(left))
    print('Top: ' + '{0:.0f}'.format(top))
    print('Face Width: ' + "{0:.0f}".format(imageWidth * box['Width']))
    print('Face Height: ' + "{0:.0f}".format(imageHeight * box['Height']))



def detect_faces(bucket, key, attributes=['ALL'], region="us-east-1"):
	rekognition = boto3.client("rekognition", region)
	response = rekognition.detect_faces(
	    Image={
			"S3Object": {
				"Bucket": bucket,
				"Name": key,
			}
		},
	    Attributes=attributes,
	)
	return response['FaceDetails']

for face in detect_faces(BUCKET, KEY):
	print "Face ({Confidence}%)".format(**face)

	# BoundingBox
	#for boundingbox in face['BoundingBox']:
	#	print "  {value}".format(**boundingbox)

	# emotions
	#for emotion in face['Emotions']:
		#print "  {Type} : {Confidence}%".format(**emotion)
	# quality
	#for quality, value in face['Quality'].iteritems():
		#print "  {quality} : {value}".format(quality=quality, value=value)
	# facial features
	for feature, data in face.iteritems():
		if feature not in FEATURES_BLACKLIST:
			print "  {feature}({data}%)".format(feature=feature, data=data)

  #cur.execute("SELECT * FROM YOUR_TABLE_NAME")


# print all the first cell of all the rows
#for row in cur.fetchall():
#    print row[0]

#db.close()


"""
	Expected output:

	Face (99.945602417%)
	  SAD : 14.6038293839%
	  HAPPY : 12.3668470383%
	  DISGUSTED : 3.81404161453%
	  Sharpness : 10.0
	  Brightness : 31.4071826935
	  Eyeglasses(False) : 99.990234375%
	  Sunglasses(False) : 99.9500656128%
	  Gender(Male) : 99.9291687012%
	  EyesOpen(True) : 99.9609146118%
	  Smile(False) : 99.8329467773%
	  MouthOpen(False) : 98.3746566772%
	  Mustache(False) : 98.7549591064%
	  Beard(False) : 92.758682251%

"""
